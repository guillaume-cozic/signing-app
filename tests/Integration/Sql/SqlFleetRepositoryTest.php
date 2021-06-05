<?php


namespace Tests\Integration\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\FleetState;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SqlFleetRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldInsertFleet()
    {
        $fleet = new Fleet(new Id($id = 'abc'), 20, Fleet::STATE_INACTIVE);

        $this->fleetRepository->save($fleet->getState());

        $this->assertDatabaseHas('fleet', ['uuid' => $id]);
        $fleetSaved = $this->fleetRepository->get($id);
        self::assertEquals($fleet, $fleetSaved);
    }

    /**
     * @test
     */
    public function shouldUpdateFleet()
    {
        $fleetState = new FleetState($id = 'abc', 20, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleetState);


        $fleetState = new FleetState($id = 'abc', 19, Fleet::STATE_ACTIVE, $rent = ['hours' => [1 => 10]]);
        $this->fleetRepository->save($fleetState);

        $this->assertDatabaseHas('fleet', ['uuid' => $id]);
        $fleetSaved = $this->fleetRepository->get($id);
        $fleetUpdatedExpected = new Fleet(new Id($id), 19, Fleet::STATE_ACTIVE, $rent);
        self::assertEquals($fleetUpdatedExpected, $fleetSaved);
    }
}
