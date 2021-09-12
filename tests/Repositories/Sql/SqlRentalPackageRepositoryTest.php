<?php


namespace Tests\Repositories\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SqlRentalPackageRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldNotGetRentalPackage()
    {
        self::assertNull($this->rentalPackageRepository->get('abc'));
    }

    /**
     * @test
     */
    public function shouldSaveRentalPackage()
    {
        $fleet = new Fleet(new Id($id = 'abc'), 20, Fleet::STATE_INACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('abc', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', 360);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        self::assertDatabaseHas('rental_package', [
            'uuid' => 'abc'
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateRentalPackage()
    {
        $fleet = new Fleet(new Id($id = 'abc'), 20, Fleet::STATE_INACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('abc', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', 360);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $rentalPackageExpected = new RentalPackage('abc', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', 300);
        $this->rentalPackageRepository->save($rentalPackageExpected->getState());

        $rentalPackageSaved = $this->rentalPackageRepository->get('abc');
        $rentalPackageExpected->setSurrogateId($rentalPackageSaved->surrogateId());
        self::assertEquals($rentalPackageExpected, $rentalPackageSaved);
    }

    /**
     * @test
     */
    public function shouldGetRentalPackageByFleet()
    {
        $fleet = new Fleet(new Id($id = 'abcd'), 20, Fleet::STATE_INACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackageExpected = new RentalPackage('abc', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', 360);
        $this->rentalPackageRepository->save($rentalPackageExpected->getState());

        $rentalPackageSaved = $this->rentalPackageRepository->getByFleet($fleet->id());
        $rentalPackageExpected->setSurrogateId($rentalPackageSaved->surrogateId());
        self::assertEquals($rentalPackageExpected, $rentalPackageSaved);
    }


    /**
     * @test
     */
    public function shouldNotGetRentalPackageByFleet()
    {
        $fleet = new Fleet(new Id($id = 'abcd'), 20, Fleet::STATE_INACTIVE);
        $this->fleetRepository->save($fleet->getState());

        self::assertNull($this->rentalPackageRepository->getByFleet($fleet->id()));
    }
}
