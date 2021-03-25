<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;
use Tests\TestCase;

class UpdateFleetTest extends TestCase
{
    private UpdateFleet $updateFleet;

    public function setUp(): void
    {
        parent::setUp();
        $this->updateFleet = app(UpdateFleet::class);
    }

    /**
     * @test
     */
    public function shouldUpdateFleet()
    {
        $fleetId = 'abc';
        $fleet = new Fleet(new Id($fleetId), 15);
        $this->fleetRepository->save($fleet->getState());

        $this->updateFleet->execute($fleetId, $newTotal = 20);

        $fleetUpdated = $this->fleetRepository->get($fleetId);
        $fleetExpected = new Fleet(new Id($fleetId), $newTotal);
        self::assertEquals($fleetExpected, $fleetUpdated, 'Fleet has not been updated');
    }

    /**
     * @test
     */
    public function shouldNotUpdateFleetWhenQtyNegative()
    {
        $fleetId = 'abc';
        $fleet = new Fleet(new Id($fleetId), 15);
        $this->fleetRepository->save($fleet->getState());

        self::expectException(NumberBoatsCantBeNegative::class);
        $this->updateFleet->execute($fleetId, $newTotal = -1);
    }

    /**
     * @test
     */
    public function shouldNotUpdateFleetWhenItDoesNotExist()
    {
        $fleetId = 'abc';

        self::expectException(FleetNotFound::class);
        $this->updateFleet->execute($fleetId, $newTotal = 20);
    }
}