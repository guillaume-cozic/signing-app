<?php


namespace Tests\Unit\Signing\BoatTrip;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;
use Tests\TestCase;

class ForceAddBoatTripTest extends TestCase
{
    private ForceAddBoatTrip $forceAddBoatTrip;

    public function setUp(): void
    {
        parent::setUp();
        $this->forceAddBoatTrip = app(ForceAddBoatTrip::class);
    }

    /**
     * @test
     */
    public function shouldForceAddBoatTrip()
    {
        $s1 = new Fleet(new Id('abc'), 5);
        $this->fleetRepository->save($s1->getState());

        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats([$s1->id() => $qty = 4])
            ->withSailor(name: $name = 'tabarly')
            ->inProgress(numberHours:2);
        $this->boatTripRepository->save($boatTrip->getState());

        $this->identityProvider->add($id = 'abcde');

        $this->forceAddBoatTrip->execute($boats = [$s1->id() => 3], $name, $numberHours = 3, null, true);

        $boatTripExpected = BoatTripBuilder::build($id)->withBoats($boats)->withSailor(name:$name)->inProgress($numberHours);
        $this->assertBoatTripAdded($id, $boatTripExpected);
    }

    private function assertBoatTripAdded(string $id, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'BoatTrip has not been added');
    }
}
