<?php


namespace Tests\Integration\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
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
        $fleet = new Fleet(new Id($id = 'abc'), 20);

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
        $fleet = new Fleet(new Id($id = 'abc'), 20);

        $this->fleetRepository->save($fleet->getState());

        $fleetUpdatedExpected = new Fleet(new Id($id), 19);
        $this->fleetRepository->save($fleetUpdatedExpected->getState());

        $this->assertDatabaseHas('fleet', ['uuid' => $id]);
        $fleetSaved = $this->fleetRepository->get($id);
        self::assertEquals($fleetUpdatedExpected, $fleetSaved);
    }
}
