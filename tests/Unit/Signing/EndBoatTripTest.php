<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class EndBoatTripTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEndABoatTrip()
    {
        $boatTrip = BoatTripBuilder::build($id = 'abc')
            ->withBoats([$supportId = Uuid::uuid4()->toString() => 2])
            ->inProgress(numberHours:2, name: $name = 'tabarly');
        $this->boatTripRepository->add($boatTrip);

        app(EndBoatTrip::class)->execute($id);

        $boatTripExpected = BoatTripBuilder::build($id = 'abc')
            ->withBoats([$supportId => 2])
            ->ended(numberHours:2, name: $name = 'tabarly');

        $this->assertBoatTripHasBeenEnded($id, $boatTripExpected);
    }

    private function assertBoatTripHasBeenEnded(string $id, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'BoatTrip has not been ended');
    }
}
