<?php


namespace Tests\Integration\Sql;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SqlBoatTripRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldInsertBoatTrip()
    {
        $fleet = new Fleet(new Id($fleetId = 'abcde'), 10);
        $this->fleetRepository->save($fleet);

        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor(name:$name = 'Tabarly')
            ->withBoats([$fleetId => 1])
            ->inProgress(1);

        $this->boatTripRepository->add($boatTrip->getState());


        self::assertDatabaseHas('boat_trip', ['uuid' => 'abcd']);
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTrip, $boatTripSaved);
    }
}
