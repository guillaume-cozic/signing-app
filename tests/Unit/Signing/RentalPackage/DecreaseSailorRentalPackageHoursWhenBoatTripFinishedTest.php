<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
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
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $endValidityDate = (new Carbon())->addDays(10);

        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank')
            ->withBoats(['fleet' => 1])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection(['fleet']), 'forfait kayak', 210);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRentalPackage = new SailorRentalPackage('sailor_rental_package_id', 'frank', 'rental_package_id', $endValidityDate, 10);
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        app(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class)->execute($boatTrip->id());

        $sailorRentalPackageSaved = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        $sailorRentalPackageExpected = new SailorRentalPackage('sailor_rental_package_id', 'frank', 'rental_package_id', $endValidityDate, 9);
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackageSaved);
    }

    /**
     * @test
     */
    public function shouldDoNothingWhenSailorDoesNotHaveRentalPackage()
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank')
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
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build('boat_trip_ended')
            ->withSailor(name:'frank')
            ->withBoats(['fleet' => 1])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        app(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class)->execute($boatTrip->id());
        self::assertTrue(true);
    }
}
