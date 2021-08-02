<?php


namespace Tests\Unit\Signing;


use App\Events\BoatTrip\BoatTripStarted;
use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Entities\User;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AddBoatTripTest extends TestCase
{
    private AddBoatTrip $addBoatTripUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->addBoatTripUseCase = app(AddBoatTrip::class);
        $this->authGateway->setUser(new User('abcde'));
    }

    /**
     * @test
     */
    public function shouldAddABoatTripWithShouldStartAtPlus5Minutes()
    {
        $s1 = new Fleet(new Id('abc'), 20);
        $this->fleetRepository->save($s1->getState());
        $s2 = new Fleet(new Id('abcd'), 15);
        $this->fleetRepository->save($s2->getState());

        $this->identityProvider->add($id = 'abc');
        $boats = [
            $s1->id() => $qty1 = 2,
            $s2->id() => $qty2 = 5,
        ];

        $shouldStartAt =  $this->dateProvider->current()->add(new \DateInterval('PT5M'));
        $this->addBoatTripUseCase->execute(
            $boats,
            $name = "tabarly",
            $numberHours = 3.5,
            null,
            null,
            null,
            $isInstructor = true,
            $isMember = true
        );

        $boatTripExpected = BoatTripBuilder::build($id)
            ->withDates(shouldStartAt: $shouldStartAt,  numberHours: 3.5)
            ->withBoats($boats)
            ->withSailor(name:$name, isInstructor:$isInstructor, isMember:$isMember)
            ->inProgress($numberHours);
        $this->assertBoatTripAdded($id, $boatTripExpected);

        Event::assertNotDispatched(BoatTripStarted::class);
    }

    /**
     * @test
     */
    public function shouldAddABoatTripWithShouldStartAtTime()
    {
        $s1 = new Fleet(new Id('abc'), 20);
        $this->fleetRepository->save($s1->getState());
        $s2 = new Fleet(new Id('abcd'), 15);
        $this->fleetRepository->save($s2->getState());

        $this->identityProvider->add($id = 'abc');
        $boats = [
            $s1->id() => $qty1 = 2,
            $s2->id() => $qty2 = 5,
        ];

        $shouldStartAt = $this->dateProvider->current()->setTime(13, 10);
        $this->addBoatTripUseCase->execute($boats, $name = "tabarly", $numberHours = 3, '13:10');

        $boatTripExpected = BoatTripBuilder::build($id)
            ->withDates(shouldStartAt: $shouldStartAt,  numberHours: 3)
            ->withBoats($boats)
            ->withSailor(name:$name)
            ->inProgress($numberHours);
        $this->assertBoatTripAdded($id, $boatTripExpected);
        Event::assertNotDispatched(BoatTripStarted::class);
    }

    /**
     * @test
     */
    public function shouldAddABoatTripWithStartInTheFuture()
    {
        $s1 = new Fleet(new Id('abc'), 20);
        $this->fleetRepository->save($s1->getState());
        $s2 = new Fleet(new Id('abcd'), 15);
        $this->fleetRepository->save($s2->getState());

        $this->identityProvider->add($id = 'abc');
        $boats = [
            $s1->id() => $qty1 = 2,
            $s2->id() => $qty2 = 5,
        ];

        $shouldStartAt = $this->dateProvider->current()->setTime(13, 10);
        $this->addBoatTripUseCase->execute($boats, $name = "tabarly", $numberHours = 3, '13:10', null, $startAuto = true);

        $boatTripExpected = BoatTripBuilder::build($id)
            ->withDates(startAt: $shouldStartAt,  numberHours: 3)
            ->withBoats($boats)
            ->withSailor(name:$name)
            ->inProgress($numberHours);
        $this->assertBoatTripAdded($id, $boatTripExpected);
        Event::assertNotDispatched(BoatTripStarted::class);
    }

    /**
     * @test
     */
    public function shouldAddBoatTripStartedNow()
    {
        $s1 = new Fleet(new Id('abc'), 20);
        $this->fleetRepository->save($s1->getState());

        $this->identityProvider->add($id = 'abc');
        $boats = [$s1->id() => $qty1 = 2];

        $this->addBoatTripUseCase->execute($boats, $name = "tabarly", $numberHours = 3, null, true);

        $boatTripExpected = BoatTripBuilder::build($id)
            ->withDates(startAt: $this->dateProvider->current(),  numberHours: 3)
            ->withBoats($boats)
            ->withSailor(name:$name)
            ->inProgress($numberHours);
        $this->assertBoatTripAdded($id, $boatTripExpected);
        Event::assertDispatched(BoatTripStarted::class);
    }

    /**
     * @test
     */
    public function shouldNotAddBoatTripWhenSupportNotAvailable()
    {
        $s1 = new Fleet(new Id('abc'), 5);
        $this->fleetRepository->save($s1->getState());

        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats([$s1->id() => $qty = 5])
            ->withSailor(name: $name = 'tabarly')
            ->inProgress(numberHours:2);

        $this->boatTripRepository->save($boatTrip->getState());

        $this->expectBoatNotAvailable();

        $this->addBoatTripUseCase->execute([$s1->id() => 3], $name, $numberHours = 3);
    }

    /**
     * @test
     */
    public function shouldNotAddBoatTripWhenNumberOfBoatsNegative()
    {
        $s1 = new Fleet(new Id('abc'), 5);
        $this->fleetRepository->save($s1->getState());

        self::expectException(NumberBoatsCantBeNegative::class);
        self::expectExceptionMessage('error.qty_cant_be_negative');
        $this->addBoatTripUseCase->execute([$s1->id() => -1], $name = 'Tabarly', $numberHours = 3);
    }

    /**
     * @test
     */
    public function boatTripReservationShouldNotImpactTheNumberOfAvailableBoatsAtTimeT()
    {
        $s1 = new Fleet(new Id('abc'), 25);
        $this->fleetRepository->save($s1->getState());

        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats([$s1->id() => $qty = 25])
            ->withSailor(name: $name = 'tabarly')
            ->notStarted((new \DateTime('+5 hours')), 2);
        $this->boatTripRepository->save($boatTrip->getState());


        $this->addBoatTripUseCase->execute([$s1->id() => 2], $name = 'Tabarly', $numberHours = 3);

        Event::assertNotDispatched(BoatTripStarted::class);
    }

    /**
     * @test
     */
    public function shouldNotCreateBoatTripWhenFleetReserved()
    {
        $s1 = new Fleet(new Id('abc'), 25);
        $this->fleetRepository->save($s1->getState());

        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats([$s1->id() => $qty = 25])
            ->withSailor(name: $name = 'tabarly')
            ->notStarted((new \DateTime('+1 hours')), 1);
        $this->boatTripRepository->save($boatTrip->getState());

        $this->expectBoatNotAvailable();

        $this->addBoatTripUseCase->execute([$s1->id() => 2], $name = 'Tabarly', $numberHours = 3);

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
