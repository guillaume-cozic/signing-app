<?php


namespace Tests\Feature\Query;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetBoatTripsListTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }

    /**
     * @test
     */
    public function shouldGetActiveBoatTripList()
    {
        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()), 20);
        $support2 = new Fleet(new Id($supportId2 = Uuid::uuid4()), 20);

        $this->fleetRepository->save($support1);
        $this->fleetRepository->save($support2);

        $support1Model = FleetModel::where('uuid', $supportId1)->first();
        $support1Model->name = $hobieCatName = 'Hobie cat 15';
        $support1Model->save();

        $support2Model = FleetModel::where('uuid', $supportId2)->first();
        $support2Model->name = 'Hobie cat 16';
        $support2Model->save();

        $boatTripDuration = new BoatTripDuration($d1 = new \DateTime('-1 hours'), $nb1 = 2);
        $boatTrip1 = new BoatTrip(id: $id1 = new Id(), boatTripDuration: $boatTripDuration, boats: $supportId1, name: 'Gaston', memberId: 'Gaston');

        $boatTripDuration = new BoatTripDuration($d2 = new \DateTime('-2 hours'), $nb2 = 1, new \DateTime('-1 hours'));
        $boatTrip2 = new BoatTrip(id: $id2 = new Id(), boatTripDuration: $boatTripDuration, boats: $supportId2, name: 'Michel', memberId: 'Michel');

        $boatTripDuration = new BoatTripDuration($d3 = new \DateTime('-1 hours'), $nb3 = 2);
        $boatTrip3 = new BoatTrip(Uuid::uuid4()id: $id3 = new Id(), boatTripDuration: $boatTripDuration, boats: Uuid::uuid4());

        $this->boatTripRepository->add($boatTrip1);
        $this->boatTripRepository->add($boatTrip2);
        $this->boatTripRepository->add($boatTrip3);

        $boatTrips = app(GetBoatTripsList::class)->execute();

        $boatTripDto1 = new BoatTripsDTo($id1->id(), $d1, null, $hobieCatName, $nb1);
        $boatTripDto3 = new BoatTripsDTo($id3->id(), $d3, null, null, $nb3);

        self::assertEquals($boatTripDto1, $boatTrips[0]);
        self::assertEquals($boatTripDto3, $boatTrips[1]);
        self::assertEquals(2, count($boatTrips));
    }
}
