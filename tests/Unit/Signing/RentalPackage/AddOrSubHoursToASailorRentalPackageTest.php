<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Exceptions\SailorRentalPackageNotFound;
use App\Signing\Signing\Domain\UseCases\RentalPackage\AddOrSubHoursToSailorRentalPackage;
use Carbon\Carbon;
use Tests\TestCase;

class AddOrSubHoursToASailorRentalPackageTest extends TestCase
{

    /**
     * @test
     */
    public function shouldNotAddHoursToUnknownSailorRentalPackage()
    {
        self::expectException(SailorRentalPackageNotFound::class);
        app(AddOrSubHoursToSailorRentalPackage::class)->execute('non_existent_sailor_rental_package_id', 1);
    }

    /**
     * @test
     */
    public function shouldAddHoursToSailorRentalPackage()
    {
        $now = Carbon::instance($this->dateProvider->current());
        $sailorRentalPackage = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package_id',
            $now->addDays(10),
            10
        );
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        app(AddOrSubHoursToSailorRentalPackage::class)->execute('sailor_rental_package_id', 1);

        $sailorRentalPackageExpected = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package_id',
            $now->addDays(10),
            11
        );
        $sailorRentalPackageSaved = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackageSaved);
    }


    /**
     * @test
     */
    public function shouldDecreaseHoursToSailorRentalPackage()
    {
        $now = Carbon::instance($this->dateProvider->current());
        $sailorRentalPackage = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package_id',
            $now->addDays(10),
            10
        );
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        app(AddOrSubHoursToSailorRentalPackage::class)->execute('sailor_rental_package_id', -1);

        $sailorRentalPackageExpected = new SailorRentalPackage(
            'sailor_rental_package_id',
            'frank',
            'rental_package_id',
            $now->addDays(10),
            9
        );
        $sailorRentalPackageSaved = $this->sailorRentalPackageRepository->get('sailor_rental_package_id');
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackageSaved);
    }

}
