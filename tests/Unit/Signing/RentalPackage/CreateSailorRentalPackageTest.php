<?php


namespace Tests\Unit\Signing\RentalPackage;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\RentalPackage\ActionSailor;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\Entities\State\SailorState;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\Exceptions\RentalPackageNotFound;
use App\Signing\Signing\Domain\Exceptions\RentalPackageValidityNegative;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use Carbon\Carbon;
use Tests\TestCase;

class CreateSailorRentalPackageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotCreateSailorRentalPackageWhenRentalPackageDoesNotExist()
    {
        self::expectException(RentalPackageNotFound::class);
        app(CreateSailorRentalPackage::class)->execute('sailor_rental_package_id', 'abc', 'frank', 10);
    }

    /**
     * @test
     * @throws NumberBoatsCantBeNegative
     * @throws RentalPackageValidityNegative
     */
    public function shouldCreateSailorRentalPackage_WithNewSailor()
    {
        $validityRentalPackage = 365;
        $fleet = $this->addFleet();
        $this->identityProvider->add($sailorId = 'sailor_id');
        $rentalPackage = $this->addRentalPackage($fleet, $validityRentalPackage);

        app(CreateSailorRentalPackage::class)->execute($sailorRentalPackageId = 'sailor_rental_package_id', $rentalPackage->id(), $sailorName = 'frank', $hours = 10);

        $sailorRentalPackageExpected = new SailorRentalPackageState(
            $sailorRentalPackageId,
            $sailorId,
            $rentalPackage->id(),
            $this->now()->addDays($validityRentalPackage),
            $hours
        );

        $sailor = $this->sailorRepository->getByName($sailorName);
        self::assertNotNull($sailor);
        self::assertEquals(new SailorState(name:$sailorName, sailorId: $sailorId), $sailor->getState());

        $this->assertSailorRentalPackageOk($sailorRentalPackageId, $sailorRentalPackageExpected);
    }

    /**
     * @test
     * @throws NumberBoatsCantBeNegative
     * @throws RentalPackageValidityNegative
     */
    public function shouldAddHoursToExistingSailorRentalPackage()
    {
        $validityRentalPackage = 365;
        $fleet = $this->addFleet();
        $rentalPackage = $this->addRentalPackage($fleet, $validityRentalPackage);

        $sailorId = 'sailor_id';
        $sailorRentalPackageId = 'sailor_rental_package_id';
        $hoursNotUsed = $this->addExistingSailorRentalPackageId($sailorRentalPackageId, $sailorId, $rentalPackage, $validityRentalPackage);

        app(CreateSailorRentalPackage::class)->execute($sailorRentalPackageId, $rentalPackage->id(),  null, $hours = 8, $sailorId);

        $sailorRentalPackageReloadedExpected = new SailorRentalPackageState(
            $sailorRentalPackageId,
            $sailorId,
            $rentalPackage->id(),
            $this->now()->addDays($validityRentalPackage),
            $hours + $hoursNotUsed,
            [new ActionSailor(ActionSailor::ADD_HOURS, $hours, Carbon::instance($this->dateProvider->current()))]
        );

        $this->assertSailorRentalPackageOk($sailorRentalPackageId, $sailorRentalPackageReloadedExpected);
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

    /**
     * @param Fleet $fleet
     * @param int $validityRentalPackage
     * @return RentalPackage
     * @throws RentalPackageValidityNegative
     */
    private function addRentalPackage(Fleet $fleet, int $validityRentalPackage): RentalPackage
    {
        $rentalPackage = new RentalPackage('rental_package', new Fleet\FleetCollection([$fleet->id()]), 'forfait kayak', $validityRentalPackage);
        $this->rentalPackageRepository->save($rentalPackage->getState());
        return $rentalPackage;
    }

    /**
     * @param string $sailorRentalPackageId
     * @param SailorRentalPackageState $sailorRentalPackageExpected
     */
    private function assertSailorRentalPackageOk(string $sailorRentalPackageId, SailorRentalPackageState $sailorRentalPackageExpected): void
    {
        $sailorRentalPackage = $this->sailorRentalPackageRepository->get($sailorRentalPackageId);
        self::assertEquals($sailorRentalPackageExpected, $sailorRentalPackage->getState());
    }

    /**
     * @param string $sailorRentalPackageId
     * @param string $sailorId
     * @param RentalPackage $rentalPackage
     * @param int $validityRentalPackage
     * @return int
     */
    private function addExistingSailorRentalPackageId(string $sailorRentalPackageId, string $sailorId, RentalPackage $rentalPackage, int $validityRentalPackage): int
    {
        $s = new SailorRentalPackageState(
            $sailorRentalPackageId,
            $sailorId,
            $rentalPackage->id(),
            (new Carbon())->addDays($validityRentalPackage - 100),
            $hoursNotUsed = 5
        );
        $this->sailorRentalPackageRepository->save($s);
        return $hoursNotUsed;
    }

}
