<?php


namespace Tests\Unit\Signing;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class EndBoatTripTest extends TestCase
{
    private EndBoatTrip $endBoatTrip;

    public function setUp(): void
    {
        parent::setUp();
        $this->endBoatTrip = app(EndBoatTrip::class);
    }

    /**
     * @test
     */
    public function shouldEndABoatTrip()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abc')
            ->withBoats([$supportId = Uuid::uuid4()->toString() => 2])
            ->inProgress(numberHours:2, name: $name = 'tabarly');
        $this->boatTripRepository->add($boatTrip);

        $this->endBoatTrip->execute($id);

        $boatTripExpected = BoatTripBuilder::build($id = 'abc')
            ->withBoats([$supportId => 2])
            ->ended(numberHours:2, name: $name = 'tabarly');

        $this->assertBoatTripHasBeenEnded($id, $boatTripExpected);
    }

    /**
     * @test
     */
    public function shouldNotEndBoatTripTwice()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abc')->ended(1, 'Tabarly');
        $this->boatTripRepository->add($boatTrip);

        self::expectException(BoatTripAlreadyEnded::class);
        $this->endBoatTrip->execute($id);
    }

    private function assertBoatTripHasBeenEnded(string $id, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'BoatTrip has not been ended');
    }
}
