<?php


namespace Tests\Integration\Sql\Read;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SqlReadBoatTripRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldGetBoatTripNearToStartOrFinish()
    {
        $fleet = new Fleet(new Id($fleetId = 'abcde'), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor(name:$name = 'Tabarly')
            ->withBoats([$fleetId => 1])
            ->withDates(startAt: new \DateTime('-56 minutes'), numberHours: 1)
            ->get()
        ;

        $this->boatTripRepository->save($boatTrip->getState());

        $boatTripsDto = $this->readBoatTripRepository->getNearToFinishOrStart();
        self::assertEquals($id, $boatTripsDto[0]->id);
    }

    /**
     * @test
     */
    public function shouldNotGetBoatTripAlreadyFinish()
    {
        $fleet = new Fleet(new Id($fleetId = 'abcde'), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor(name:$name = 'Tabarly')
            ->withBoats([$fleetId => 1])
            ->ended(1)
        ;

        $this->boatTripRepository->save($boatTrip->getState());

        $boatTripsDto = $this->readBoatTripRepository->getNearToFinishOrStart();
        self::assertEmpty($boatTripsDto);
    }

    /**
     * @test
     */
    public function shouldGetBoatTripNearToStart()
    {
        $fleet = new Fleet(new Id($fleetId = 'abcde'), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor(name:$name = 'Tabarly')
            ->withBoats([$fleetId => 1])
            ->withDates(shouldStartAt: new \DateTime('-4 minutes'), numberHours: 1)
            ->get()
        ;

        $this->boatTripRepository->save($boatTrip->getState());

        $boatTripsDto = $this->readBoatTripRepository->getNearToFinishOrStart();
        self::assertEquals($id, $boatTripsDto[0]->id);
    }
}
