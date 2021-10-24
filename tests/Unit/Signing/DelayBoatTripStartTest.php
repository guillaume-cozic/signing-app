<?php


namespace Tests\Unit\Signing;


use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;
use App\Signing\Signing\Domain\UseCases\DelayBoatTripStart;
use Tests\TestCase;

class DelayBoatTripStartTest extends TestCase
{
    private DelayBoatTripStart $delayBoatTripStart;

    public function setUp(): void
    {
        parent::setUp();
        $this->delayBoatTripStart = app(DelayBoatTripStart::class);
    }

    /**
     * @test
     */
    public function shouldDelayBoatTripStart()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor('Tabarly')
            ->inProgress(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $startAt = clone $boatTrip->getState()->startAt();

        $this->delayBoatTripStart->execute($id, 10);

        $newStartDate = $startAt->add(\DateInterval::createFromDateString('+10 minutes'));
        $boatTripExpected = BoatTripBuilder::build($id)
            ->withSailor('Tabarly')
            ->fromState(1, $newStartDate, null, null);

        $this->assertBoatTripStartHasBeenDelayed($id, $boatTripExpected);
    }

    /**
     * @test
     */
    public function shouldNotDelayBoatTripWhenAlreadyEnded()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor('Tabarly')
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());


        self::expectException(BoatTripAlreadyEnded::class);
        $this->delayBoatTripStart->execute($id, 10);
    }

    /**
     * @test
     */
    public function shouldNotDelayBoatTripWhenTimeNegative()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abcd')
            ->withSailor('Tabarly')
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());


        self::expectException(TimeCantBeNegative::class);
        $this->delayBoatTripStart->execute($id, -10);
    }

    private function assertBoatTripStartHasBeenDelayed(string $id, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'Start date has not been delayed');
    }
}
