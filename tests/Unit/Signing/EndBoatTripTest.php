<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
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
        $boatTripDuration = new BoatTripDuration($dateStart = new \DateTime(), 2);
        $boatTrip = new BoatTrip($id = new Id(), $boatTripDuration, $supportId = Uuid::uuid4(), 2, 'tabarly');
        $this->boatTripRepository->add($boatTrip);

        app(EndBoatTrip::class)->execute($id->id());

        $boatTripDuration = new BoatTripDuration($dateStart, 2, $this->dateProvider->current());
        $boatTripExpected = new BoatTrip($id, $boatTripDuration, $supportId, 2, 'tabarly');

        $boatTripSaved = $this->boatTripRepository->get($id->id());
        self::assertEquals($boatTripExpected, $boatTripSaved);
    }
}
