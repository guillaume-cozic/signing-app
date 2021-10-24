<?php declare(strict_types=1);


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\ActionSailor;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\UseCases\RentalPackage\DecreaseSailorRentalPackageHoursWhenBoatTripFinished;
use Carbon\Carbon;
use Tests\TestCase;

class DecreaseSailorRentalPackageHoursWhenBoatTripFinishedTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDecreaseSailorRentalPackageHours()
    {
        $this->addFleet();

        $endValidityDate = (new Carbon())->addDays(10);

        $sailorId = 'sailorId';
        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank', sailorId: $sailorId)
            ->withBoats(['fleet' => 1])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection(['fleet']), 'forfait kayak', 210);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRentalPackage = new SailorRentalPackage('sailor_rental_package_id', $sailorId, 'rental_package_id', $endValidityDate, 10);
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        app(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class)->execute($boatTrip->id());

        $sailorRentalPackageSaved = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        $sailorRentalPackageExpected = new SailorRentalPackageState(
            'sailor_rental_package_id',
            $sailorId,
            'rental_package_id',
            $endValidityDate,
            9,
            [new ActionSailor(ActionSailor::SAIL_HOURS, 1, Carbon::instance($this->dateProvider->current()))]
        );
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackageSaved->getState());
    }

    /**
     * @test
     */
    public function shouldNotDecreaseSailorRentalPackageHours()
    {
        $this->addFleet();

        $endValidityDate = (new Carbon())->addDays(10);

        $sailorId = 'sailorId';
        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank', sailorId: $sailorId)
            ->withBoats(['fleet' => 1])
            ->withOptions(['do_not_decrease_hours' => true])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection(['fleet']), 'forfait kayak', 210);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRentalPackage = new SailorRentalPackage('sailor_rental_package_id', $sailorId, 'rental_package_id', $endValidityDate, 10);
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        app(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class)->execute($boatTrip->id());

        $sailorRentalPackageSaved = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        $sailorRentalPackageExpected = new SailorRentalPackageState(
            'sailor_rental_package_id',
            $sailorId,
            'rental_package_id',
            $endValidityDate,
            10
        );
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackageSaved->getState());
    }

    /**
     * @test
     */
    public function shouldDoNothingWhenSailorDoesNotHaveRentalPackage()
    {
        $this->addFleet();

        $sailorId = 'sailorId';
        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank', sailorId: $sailorId)
            ->withBoats(['fleet' => 1])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection(['fleet']), 'forfait kayak', 210);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        app(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class)->execute($boatTrip->id());
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldDoNothingWhenNoRentalPackageForTheFleet()
    {
        $this->addFleet();

        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank')
            ->withBoats(['fleet' => 1])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        app(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class)->execute($boatTrip->id());
        self::assertTrue(true);
    }

    private function addFleet(): void
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());
    }
}
