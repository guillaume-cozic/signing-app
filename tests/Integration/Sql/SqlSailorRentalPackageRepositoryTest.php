<?php


namespace Tests\Integration\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\ActionSailor;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SqlSailorRentalPackageRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldNoGetSailorRental()
    {
        self::assertNull($this->sailorRentalPackageRepository->get('abc'));
    }

    /**
     * @test
     */
    public function shouldSaveSailorRental()
    {
        $now = Carbon::instance($this->dateProvider->current());
        $sailorRental = new SailorRentalPackage('abc','tabarly', 'rental_package_id', $now, 10);
        $this->sailorRentalPackageRepository->save($sailorRental->getState());

        self::assertDatabaseHas('sailor_rental_package', ['uuid' => 'abc']);
        self::assertDatabaseHas('sailor', ['name' => 'tabarly']);
    }

    /**
     * @test
     */
    public function shouldUpdateSailorRental()
    {
        $now = Carbon::instance($this->dateProvider->current());

        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$fleet->id()]), 'forfait', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRental = new SailorRentalPackage('abc','tabarly', 'rental_package_id', $now, 10, );
        $this->sailorRentalPackageRepository->save($sailorRental->getState());

        $sailorRental = new SailorRentalPackage('abc','tabarly', 'rental_package_id', $now, 9);
        $this->sailorRentalPackageRepository->save($sailorRental->getState());

        self::assertDatabaseHas('sailor_rental_package', ['uuid' => 'abc', 'hours' => 9]);
        self::assertDatabaseHas('sailor', ['name' => 'tabarly']);
    }

    /**
     * @test
     */
    public function shouldGetSailorRental()
    {
        $now = Carbon::instance($this->dateProvider->current())->startOfDay();
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$fleet->id()]), 'forfait', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $actionDate = Carbon::instance($this->dateProvider->current());
        $actionDate->setMicrosecond(0)->setSeconds(0);
        $actions = [new ActionSailor(ActionSailor::ADD_HOURS, 1, $actionDate)];
        $sailorRentalExpected = new SailorRentalPackage('abc','tabarly', 'rental_package_id', $now, 10, $actions);
        $this->sailorRentalPackageRepository->save($sailorRentalExpected->getState());

        $sailorRentalSaved = $this->sailorRentalPackageRepository->get('abc');
        self::assertEquals($sailorRentalExpected, $sailorRentalSaved);
    }

    /**
     * @test
     */
    public function shouldGetSailorRentalByNameAndPackageId()
    {
        $now = Carbon::instance($this->dateProvider->current())->startOfDay();

        $name = "tabarly";

        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$fleet->id()]), 'forfait', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRentalExpected = new SailorRentalPackage('abc','tabarly', 'rental_package_id', $now, 10);
        $this->sailorRentalPackageRepository->save($sailorRentalExpected->getState());

        $sailorRentalSaved = $this->sailorRentalPackageRepository->getByNameAndRentalPackage($name, 'rental_package_id');
        self::assertEquals($sailorRentalExpected, $sailorRentalSaved);
    }


    /**
     * @test
     */
    public function shouldNotGetSailorRental_WhenNoRentalPackage()
    {
        $now = Carbon::instance($this->dateProvider->current())->startOfDay();

        $name = "tabarly";

        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $sailorRentalExpected = new SailorRentalPackage('abc','tabarly', 'rental_package_id', $now, 10);
        $this->sailorRentalPackageRepository->save($sailorRentalExpected->getState());

        self::assertNull($this->sailorRentalPackageRepository->getByNameAndRentalPackage($name, 'rental_package_id'));
    }

    /**
     * @test
     */
    public function shouldNotGetSailorRental_WhenNoSailor()
    {
        $name = "tabarly";

        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$fleet->id()]), 'forfait', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());


        self::assertNull($this->sailorRentalPackageRepository->getByNameAndRentalPackage($name, 'rental_package_id'));
    }
}
