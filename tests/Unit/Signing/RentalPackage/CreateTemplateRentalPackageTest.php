<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Exceptions\RentalPackageValidityNegative;
use App\Signing\Signing\Domain\Exceptions\RentalPackageWithoutFleet;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateRentalPackage;
use Tests\TestCase;

class CreateTemplateRentalPackageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCreateRentalPackageWithAnyFleet()
    {
        self::expectException(RentalPackageWithoutFleet::class);
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', []);
    }

    /**
     * @test
     */
    public function shouldNotCreateRentalPackageWithUnknownFleet()
    {
        self::expectException(FleetNotFound::class);
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', ['1']);
    }

    /**
     * @test
     */
    public function shouldNotCreateRentalPackageWithValidityNegative()
    {
        $fleet = new Fleet(new Id(), 10, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet->getState());

        self::expectException(RentalPackageValidityNegative::class);
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', [$fleet->id()], -1);
    }

    /**
     * @test
     */
    public function shouldCreateRentalPackage()
    {
        $fleet = new Fleet(new Id(), 10, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet->getState());

        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', [$fleet->id()]);

        $rentalPackage = $this->rentalPackageRepository->get($rentalPackageId);
        $rentalPackageExpected = new RentalPackage($rentalPackageId, [$fleet->id()]);
        self::assertEquals($rentalPackageExpected, $rentalPackage);
    }
}
