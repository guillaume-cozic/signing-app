<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\FleetState;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackageState;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\UseCases\RentalPackage\EditRentalPackage;
use Tests\TestCase;

class EditTemplateRentalPackageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEditRentalPackage()
    {
        $fleet = new FleetState('fleet', 10, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet);

        $fleet2 = new FleetState('fleet2', 10, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet2);

        $rentalPackage = new RentalPackageState('abc', ['fleet'], 730);
        $this->rentalPackageRepository->save($rentalPackage);

        $name = 'Forfait kayak / paddle';
        app(EditRentalPackage::class)->execute('abc', ['fleet2'], $name, 365);

        $rentalPackageEdited = $this->rentalPackageRepository->get('abc');
        $rentalPackageExpected = new RentalPackageState('abc', ['fleet2'], $name, 365);

        self::assertEquals($rentalPackageExpected, $rentalPackageEdited->getState());
    }

    /**
     * @test
     */
    public function shouldNotUpdateRentalPackageWithUnknownFleet()
    {
        $name = 'Forfait kayak / paddle';
        self::expectException(FleetNotFound::class);
        app(EditRentalPackage::class)->execute($rentalPackageId = 'abc', ['1'], $name);
    }
}
