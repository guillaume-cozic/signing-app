<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Exceptions\RentalPackageNotFound;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use Carbon\Carbon;
use Tests\TestCase;

class CreateSailorRentalPackageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCreateSailorRentalPackageWhenTemplateRentalPackageDoesNotExist()
    {
        self::expectException(RentalPackageNotFound::class);
        app(CreateSailorRentalPackage::class)->execute('sailor_rental_package_id', 'abc', 'frank', 10);
    }

    /**
     * @test
     */
    public function shouldCreateSailorRentalPackage()
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', $validity = 365);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        app(CreateSailorRentalPackage::class)->execute('sailor_rental_package_id', 'rental_package', 'frank', $hours = 10);

        $now = Carbon::instance($this->dateProvider->current());
        $sailorRentalPackageExpected = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package',
            $now->addDays($validity),
            $hours
        );

        $sailorRentalPackage = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackage);
    }

    /**
     * @test
     */
    public function shouldAddHoursToExistingSailorRentalPackage()
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', $validity = 365);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $s = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package',
            (new Carbon())->addDays($validity - 100),
            $hoursNotUsed = 5
        );
        $this->sailorRentalPackageRepository->save($s->getState());

        app(CreateSailorRentalPackage::class)->execute('sailor_rental_package_id', 'rental_package', 'frank', $hours = 8);

        $now = Carbon::instance($this->dateProvider->current());
        $sailorRentalPackageExpected = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package',
            $now->addDays($validity),
            $hours + $hoursNotUsed
        );

        $sailorRentalPackage = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackage);
    }

}
