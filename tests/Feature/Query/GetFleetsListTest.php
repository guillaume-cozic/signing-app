<?php


namespace Tests\Feature\Query;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Dto\FleetDto;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetFleetsListTest extends TestCase
{
    use DatabaseTransactions;

    private GetFleetsList $getFleetsList;

    public function setUp(): void
    {
        parent::setUp();
        $this->getFleetsList = app(GetFleetsList::class);
    }

    /**
     * @test
     */
    public function shouldGetListFleets()
    {
        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()->toString()), 20);
        $support2 = new Fleet(new Id($supportId2 = Uuid::uuid4()->toString()), 15);
        $support3 = new Fleet(new Id($supportId3 = Uuid::uuid4()->toString()), 15);

        $this->fleetRepository->save($support1->getState());
        $this->fleetRepository->save($support2->getState());
        $this->fleetRepository->save($support3->getState());

        $support1Model = FleetModel::where('uuid', $supportId1)->first();
        $support1Model->name = $hobieCatName = 'Hobie cat 15';
        $support1Model->save();

        $support2Model = FleetModel::where('uuid', $supportId2)->first();
        $support2Model->name = 'Hobie cat 16';
        $support2Model->save();

        $support3Model = FleetModel::where('uuid', $supportId3)->first();
        $support3Model->name = 'First class 8';
        $support3Model->save();

        $fleetsExpected = [
            new FleetDto($supportId1, 'Hobie cat 15', 20, 'active'),
            new FleetDto($supportId2, 'Hobie cat 16', 15, 'active'),
        ];
        $fleetsRetrieved = $this->getFleetsList->execute();
        self::assertEquals($fleetsExpected[0], $fleetsRetrieved[0]);
        self::assertEquals($fleetsExpected[1], $fleetsRetrieved[1]);
        self::assertCount(3, $fleetsRetrieved);
    }

    /**
     * @test
     */
    public function shouldGetOnlyEnableFleet()
    {
        $support1 = new Fleet(new Id($supportId1 = 'ab'), 20);
        $support2 = new Fleet(new Id($supportId2 = 'abc'), 15, Fleet::STATE_INACTIVE);
        $support3 = new Fleet(new Id($supportId3 = 'abcd'), 15, Fleet::STATE_INACTIVE);

        $this->fleetRepository->save($support1->getState());
        $this->fleetRepository->save($support2->getState());
        $this->fleetRepository->save($support3->getState());

        $fleetsRetrieved = $this->getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]]);

        self::assertCount(1, $fleetsRetrieved);
    }

    /**
     * @test
     */
    public function getFleetsOfMySailingClub()
    {
        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()->toString()), 20);
        $support2 = new Fleet(new Id($supportId2 = Uuid::uuid4()->toString()), 15);

        $this->fleetRepository->save($support1->getState());
        $this->fleetRepository->save($support2->getState());

        // we change the sailing club id
        $this->contextService->setSailingClubId(2);
        $this->contextService->set();

        $support3 = new Fleet(new Id($supportId3 = Uuid::uuid4()->toString()), 15);
        $this->fleetRepository->save($support3->getState());


        // we restore the sailing club id
        $this->contextService->setSailingClubId(1);
        $this->contextService->set();
        $fleetsRetrieved = $this->getFleetsList->execute();

        self::assertCount(2, $fleetsRetrieved);
    }
}

