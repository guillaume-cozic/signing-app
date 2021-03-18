<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use Tests\TestCase;

class AddBoatTripTest extends TestCase
{
    private AddBoatTrip $addBoatTripUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->addBoatTripUseCase = app(AddBoatTrip::class);
    }

    /**
     * @test
     */
    public function shouldAddABoatTrip()
    {
        ($s1 = new Fleet(new Id('abc'), 20))->create();
        ($s2 = new Fleet(new Id('abcd'), 15))->create();

        $this->identityProvider->add($id = 'abc');
        $boats = [
            $s1->id() => $qty1 = 2,
            $s2->id() => $qty2 = 5,
        ];

        $this->addBoatTripUseCase->execute($boats, $name = "tabarly", $numberHours = 3);

        $boatTripExpected = BoatTripBuilder::build($id)->withBoats($boats)->inProgress($numberHours, $name);
        $this->assertBoatTripAdded($id, $boatTripExpected);
    }

    /**
     * @test
     */
    public function shouldNotAddBoatTripWhenSupportNotAvailable()
    {
        ($s1 = new Fleet(new Id('abc'), 5))->create();

        BoatTripBuilder::build('abc')
            ->withBoats([$s1->id() => $qty = 5])
            ->inProgress(numberHours:2, name: $name = 'tabarly')
            ->create();

        $this->expectBoatNotAvailable();

        $this->addBoatTripUseCase->execute([$s1->id() => 3], $name, $numberHours = 3);
    }

    private function assertBoatTripAdded(string $id, BoatTrip $boatTripExpected): void
    {
        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved, 'BoatTrip has not been added');
    }

    private function expectBoatNotAvailable(): void
    {
        self::expectException(BoatNotAvailable::class);
        self::expectExceptionMessage('error.support_not_available');
    }
}
