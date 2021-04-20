<?php


namespace Tests\Unit\Signing\BoatTrip;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\BoatTrip\StartBoatTrip;
use Tests\TestCase;

class StartBoatTripTest extends TestCase
{
    private StartBoatTrip $startBoatTrip;

    public function setUp(): void
    {
        parent::setUp();
        $this->startBoatTrip = app(StartBoatTrip::class);
    }

    /**
     * @test
     */
    public function shouldStartBoatTrip()
    {
        $fleet = new Fleet(new Id(), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build('abc')
            ->withSailor('tabarly')
            ->withBoats([$fleet->id() => 1])
            ->notStarted($shouldStart = $this->dateProvider->current(), 1);
        $this->boatTripRepository->save($boatTrip->getState());

        $this->startBoatTrip->execute('abc');

        $boatTripExpected = BoatTripBuilder::build('abc')
            ->withSailor('tabarly')
            ->withBoats([$fleet->id() => 1])
            ->fromState(numberHours:  1, startAt: $this->dateProvider->current(), shouldStartAt: $shouldStart);
        $boatTripSaved = $this->boatTripRepository->get('abc');
        self::assertEquals($boatTripExpected, $boatTripSaved);
    }

    /**
     * @test
     */
    public function shouldNotStartBoatTripTwice()
    {
        $fleet = new Fleet(new Id(), 10);
        $this->fleetRepository->save($fleet->getState());

        $boatTrip = BoatTripBuilder::build('abc')
            ->withSailor('tabarly')
            ->withBoats([$fleet->id() => 1])
            ->fromState(numberHours: 1, startAt: new \DateTime('-10 mins'));
        $this->boatTripRepository->save($boatTrip->getState());

        $this->startBoatTrip->execute('abc');

        $boatTripExpected = clone $boatTrip;
        $boatTripSaved = $this->boatTripRepository->get('abc');
        self::assertEquals($boatTripExpected, $boatTripSaved);
    }

}
