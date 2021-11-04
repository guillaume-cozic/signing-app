<?php


namespace Tests\Repositories\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\ActionSailor;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
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
        $sailor = new Sailor(name:'tabarly', sailorId: $sailorId = 'sailorId');
        $this->sailorRepository->save($sailor->getState());

        $sailorRental = new SailorRentalPackage('abc',$sailorId, 'rental_package_id', $this->now(), 10);

        $this->sailorRentalPackageRepository->save($sailorRental->getState());

        self::assertDatabaseHas('sailor_rental_package', ['uuid' => 'abc']);
        self::assertDatabaseHas('sailor', ['name' => 'tabarly']);
    }

    /**
     * @test
     * @throws NumberBoatsCantBeNegative
     */
    public function shouldUpdateSailorRental()
    {
        $fleet = $this->addFleet();

        $sailor = new Sailor(name:'Guillaume', sailorId: $sailorId = 'sailor');
        $this->sailorRepository->save($sailor->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$fleet->id()]), 'forfait', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRental = new SailorRentalPackage('abc',$sailorId, 'rental_package_id', $this->now(), 10, );
        $this->sailorRentalPackageRepository->save($sailorRental->getState());

        $sailorRental = new SailorRentalPackage('abc',$sailorId, 'rental_package_id', $this->now(), 9);
        $this->sailorRentalPackageRepository->save($sailorRental->getState());

        self::assertDatabaseHas('sailor_rental_package', ['uuid' => 'abc', 'hours' => 9]);
    }

    /**
     * @test
     * @throws NumberBoatsCantBeNegative
     */
    public function shouldGetSailorRental()
    {
        $now = Carbon::instance($this->dateProvider->current())->startOfDay();
        $fleet = $this->addFleet();

        $sailorId = 'sailorId';
        $sailor = new Sailor(name:'Guillaume', sailorId: $sailorId);
        $this->sailorRepository->save($sailor->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$fleet->id()]), 'forfait', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $actionDate = Carbon::instance($this->dateProvider->current());
        $actionDate->setMicrosecond(0)->setSeconds(0);
        $actions = [new ActionSailor(ActionSailor::ADD_HOURS, 1, $actionDate)];
        $sailorRentalExpected = new SailorRentalPackage('abc',$sailorId, 'rental_package_id', $now, 10, $actions);
        $this->sailorRentalPackageRepository->save($sailorRentalExpected->getState());

        $sailorRentalSaved = $this->sailorRentalPackageRepository->get('abc');
        self::assertEquals($sailorRentalExpected, $sailorRentalSaved);
    }

    /**
     * @return Fleet
     * @throws NumberBoatsCantBeNegative
     */
    private function addFleet(): Fleet
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());
        return $fleet;
    }
}
