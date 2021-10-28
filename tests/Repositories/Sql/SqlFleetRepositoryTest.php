<?php


namespace Tests\Repositories\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetState;
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
        $fleet = new FleetState($id = 'abc', 10, Fleet::STATE_ACTIVE, ['name' => ['fr' => 'hobie cat 15']]);
        $this->fleetRepository->save($fleet);

        $this->assertDatabaseHas('fleet', ['uuid' => $id]);
        $fleetSaved = $this->fleetRepository->get($id);

        $fleetExpected = new Fleet(new Id($id), 10);
        self::assertEquals($fleetExpected->getState(), $fleetSaved->getState());
    }

    /**
     * @test
     */
    public function shouldUpdateFleet()
    {
        $fleet = new FleetState($id = 'abc', 20, Fleet::STATE_ACTIVE, ['name' => ['fr' => 'hobie cat 15']]);
        $this->fleetRepository->save($fleet);

        $fleetUpdatedExpected = new FleetState($id = 'abc', 19, Fleet::STATE_ACTIVE, ['name' => ['fr' => 'hobie cat 15']]);
        $this->fleetRepository->save($fleetUpdatedExpected);

        $this->assertDatabaseHas('fleet', ['uuid' => $id, 'total_available' => 19]);
    }
}
