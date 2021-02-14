<?php


namespace Tests\Feature\Query;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
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
        $support1 = new Support(new Id($supportId1 = Uuid::uuid4()), 20);
        $support2 = new Support(new Id($supportId2 = Uuid::uuid4()), 20);

        $this->supportRepository->save($support1);
        $this->supportRepository->save($support2);

        $boatTripDuration = new BoatTripDuration(new \DateTime('-1 hours'), 2);
        $boatTrip1 = new BoatTrip(id:new Id(), boatTripDuration:$boatTripDuration, supportId: $supportId1, qty: 2, name: 'Gaston');

        $boatTripDuration = new BoatTripDuration(new \DateTime('-2 hours'), 1, new \DateTime('-1 hours'));
        $boatTrip2 = new BoatTrip(id:new Id(), boatTripDuration:$boatTripDuration, supportId: $supportId2, qty: 2, name: 'Michel');

        $boatTripDuration = new BoatTripDuration(new \DateTime('-1 hours'), 2);
        $boatTrip3 = new BoatTrip(id:new Id(), boatTripDuration:$boatTripDuration, memberId: Uuid::uuid4());

        $this->boatTripRepository->add($boatTrip1);
        $this->boatTripRepository->add($boatTrip2);
        $this->boatTripRepository->add($boatTrip3);

        $boatTrips = app(GetBoatTripsList::class)->execute();
        self::assertEquals(2, $boatTrips);
    }
}
