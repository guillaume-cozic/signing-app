<?php


namespace Tests\Feature\Query;


use App\Models\User;
use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetBoatTripsListTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldGetActiveBoatTripList()
    {
        $user = new User();
        $user->firstname = 'Gaston';
        $user->surname = 'Cozic';
        $user->email = 'gaston@cozic.fr';
        $user->uuid = $memberId = 'abcd';
        $user->password = bcrypt('secret');
        $user->save();

        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()->toString()), 20);
        $support2 = new Fleet(new Id($supportId2 = Uuid::uuid4()->toString()), 20);

        $this->fleetRepository->save($support1->getState());
        $this->fleetRepository->save($support2->getState());

        $support1Model = FleetModel::where('uuid', $supportId1)->first();
        $support1Model->name = $hobieCatName = 'Hobie cat 15';
        $support1Model->save();

        $support2Model = FleetModel::where('uuid', $supportId2)->first();
        $support2Model->name = 'Hobie cat 16';
        $support2Model->save();

        $boatTrip1 = BoatTripBuilder::build('abc')
            ->withBoats([$supportId1 => 1])
            ->withSailor(name:'Tabarly')
            ->inProgress(1);

        $boatTrip2 = BoatTripBuilder::build('abcd')
            ->withSailor(memberId:$memberId)
            ->inProgress(1);

        $boatTrip3 = BoatTripBuilder::build('abcde')
            ->withSailor(memberId:$memberId)
            ->ended(1);

        $this->boatTripRepository->save($boatTrip1->getState());
        $this->boatTripRepository->save($boatTrip2->getState());
        $this->boatTripRepository->save($boatTrip3->getState());

        $boatTrips = app(GetBoatTripsList::class)->execute();

        $boatTripDto1 = new BoatTripsDTo('abc', new Carbon($this->dateProvider->current()), null, 'Tabarly', [$hobieCatName => 1], 1);
        $boatTripDto2 = new BoatTripsDTo('abcd', new Carbon($this->dateProvider->current()), null, 'Gaston Cozic', [], 1);

        self::assertEquals($boatTripDto1, $boatTrips[0]);
        self::assertEquals($boatTripDto2, $boatTrips[1]);
        self::assertEquals(2, count($boatTrips));
    }

    /**
     * @test
     */
    public function shouldGetInProgressBoatTripsOfMySailingClub()
    {
        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()->toString()), 20);
        $support2 = new Fleet(new Id($supportId2 = Uuid::uuid4()->toString()), 20);

        $this->fleetRepository->save($support1->getState());
        $this->fleetRepository->save($support2->getState());

        $support1Model = FleetModel::where('uuid', $supportId1)->first();
        $support1Model->name = $hobieCatName = 'Hobie cat 15';
        $support1Model->save();

        $support2Model = FleetModel::where('uuid', $supportId2)->first();
        $support2Model->name = 'Hobie cat 16';
        $support2Model->save();

        $boatTrip1 = BoatTripBuilder::build('abc')
            ->withBoats([$supportId1 => 1])
            ->withSailor(name:'Tabarly')
            ->inProgress(1);

        $boatTrip2 = BoatTripBuilder::build('abcd')
            ->withSailor(name:'Cousteau')
            ->inProgress(1);

        $this->boatTripRepository->save($boatTrip1->getState());
        $this->boatTripRepository->save($boatTrip2->getState());



        // we change the sailing club id
        $this->contextService->setSailingClubId(2);
        $this->contextService->set();

        $boatTrip3 = BoatTripBuilder::build('abcde')
            ->withSailor('Corto maltese')
            ->inProgress(1);
        $this->boatTripRepository->save($boatTrip3->getState());


        // we change the sailing club id
        $this->contextService->setSailingClubId(1);
        $this->contextService->set();

        $boatTrips = app(GetBoatTripsList::class)->execute();

        $boatTripDto1 = new BoatTripsDTo('abc', new Carbon($this->dateProvider->current()), null, 'Tabarly', [$hobieCatName => 1], 1);
        $boatTripDto2 = new BoatTripsDTo('abcd', new Carbon($this->dateProvider->current()), null, 'Cousteau', [], 1);

        self::assertEquals($boatTripDto1, $boatTrips[0]);
        self::assertEquals($boatTripDto2, $boatTrips[1]);
        self::assertEquals(2, count($boatTrips));
    }
}
