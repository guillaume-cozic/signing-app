<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackageState;
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
        $name = 'Forfait Kayak/Paddle';
        self::expectException(RentalPackageWithoutFleet::class);
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', [], $name);
    }

    /**
     * @test
     */
    public function shouldNotCreateRentalPackageWithUnknownFleet()
    {
        $name = 'Forfait Kayak/Paddle';
        self::expectException(FleetNotFound::class);
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', ['1'], $name);
    }

    /**
     * @test
     */
    public function shouldNotCreateRentalPackageWithValidityNegative()
    {
        $fleet = new Fleet(new Id(), 10, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $name = 'Forfait Kayak/Paddle';
        self::expectException(RentalPackageValidityNegative::class);
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', [$fleet->id()], $name, -1);
    }

    /**
     * @test
     */
    public function shouldCreateRentalPackage()
    {
        $fleet = new Fleet(new Id(), 10, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $name = 'Forfait Kayak/Paddle';
        app(CreateRentalPackage::class)->execute($rentalPackageId = 'abc', [$fleet->id()], $name, 730 );

        $rentalPackage = $this->rentalPackageRepository->get($rentalPackageId);
        $rentalPackageExpected = new RentalPackageState($rentalPackageId, [$fleet->id()], $name, 730);
        self::assertEquals($rentalPackageExpected, $rentalPackage->getState());
    }
}
