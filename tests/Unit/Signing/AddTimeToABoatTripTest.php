<?php


namespace Tests\Unit\Signing;

use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;
use App\Signing\Signing\Domain\UseCases\AddTimeToBoatTrip;
use Tests\TestCase;

class AddTimeToABoatTripTest extends TestCase
{
    private AddTimeToBoatTrip $addTimeToBoatTrip;

    public function setUp(): void
    {
        parent::setUp();
        $this->addTimeToBoatTrip = app(AddTimeToBoatTrip::class);
    }

    /**
     * @test
     */
    public function shouldNotAddTimeToEndedBoatTrip()
    {
        $boatTrip = BoatTripBuilder::build($boatTripId = 'abcd')
            ->withBoats($boats = ['abc' => 1])
            ->withSailor(name:'Tabarly')
            ->ended($numberHours = 2);
        $this->boatTripRepository->save($boatTrip->getState());

        self::expectException(BoatTripAlreadyEnded::class);

        $this->addTimeToBoatTrip->execute($boatTripId, 1);
    }

    /**
     * @test
     */
    public function shouldAddTimeToBoatTrip()
    {
        $boatTrip = BoatTripBuilder::build($boatTripId = 'abcd')
            ->withBoats($boats = ['abc' => 1])
            ->withSailor(name: $name = 'Tabarly')
            ->inProgress($numberHours = 2);
        $this->boatTripRepository->save($boatTrip->getState());

        $this->addTimeToBoatTrip->execute($boatTripId, 0.5);

        $boatTripExpected = BoatTripBuilder::build($boatTripId)
            ->withBoats($boats)
            ->withSailor(name: $name)
            ->inProgress(2.5);

        $this->assertTimeAddedToBoatTrip($boatTripId, $boatTripExpected);
    }


    /**
     * @test
     */
    public function shouldNotAddNegativeTimeToBoatTrip()
    {
        $boatTrip = BoatTripBuilder::build($boatTripId = 'abcd')
            ->withBoats($boats = ['abc' => 1])
            ->withSailor(name: 'Tabarly')
            ->inProgress($numberHours = 2);
        $this->boatTripRepository->save($boatTrip->getState());

        self::expectException(TimeCantBeNegative::class);
        $this->addTimeToBoatTrip->execute($boatTripId, -1);
    }

    private function assertTimeAddedToBoatTrip(string $boatTripId, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($boatTripId);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'Time has not be correctly added to boat trip');
    }
}
