<?php


namespace Tests\Unit\Signing;


use App\Events\BoatTrip\BoatTripEnded;
use App\Signing\Shared\Entities\User;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class EndBoatTripTest extends TestCase
{
    private EndBoatTrip $endBoatTrip;

    public function setUp(): void
    {
        parent::setUp();
        $this->endBoatTrip = app(EndBoatTrip::class);
        $this->authGateway->setUser(new User('abcde'));
    }

    /**
     * @test
     */
    public function shouldEndABoatTrip()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abc')
            ->withBoats([$supportId = Uuid::uuid4()->toString() => 2])
            ->withSailor(name: $name = 'Tabarly')
            ->inProgress(numberHours:2);
        $this->boatTripRepository->save($boatTrip->getState());

        $this->endBoatTrip->execute($id);

        $boatTripExpected = BoatTripBuilder::build($id = 'abc')
            ->withBoats([$supportId => 2])
            ->withSailor(name: $name)
            ->ended(numberHours:2);

        $this->assertBoatTripHasBeenEnded($id, $boatTripExpected);
        Event::assertDispatched(BoatTripEnded::class, function (BoatTripEnded $boatTripEnded){
            return $boatTripEnded->userId = 'abcde' && $boatTripEnded->boatTripId = 'abc';
        });
    }

    /**
     * @test
     */
    public function shouldNotEndBoatTripTwice()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abc')
            ->withSailor(name: 'Tabarly')
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        self::expectException(BoatTripAlreadyEnded::class);
        $this->endBoatTrip->execute($id);
    }

    private function assertBoatTripHasBeenEnded(string $id, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'BoatTrip has not been ended');
    }
}
