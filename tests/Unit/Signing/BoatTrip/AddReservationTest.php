<?php

namespace Tests\Unit\Signing\BoatTrip;

use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTripDuration;
use App\Signing\Signing\Domain\Entities\BoatTrip\Reservation;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\UseCases\BoatTrip\AddReservation;
use Carbon\Carbon;
use Tests\TestCase;

class AddReservationTest extends TestCase
{
    private AddReservation $addReservation;

    public function setUp(): void
    {
        parent::setUp();
        $this->addReservation = app(AddReservation::class);
    }

    /**
     * @test
     */
    public function shouldAddReservation()
    {
        $s1 = new Fleet(new Id('abc'), 25);
        $this->fleetRepository->save($s1->getState());
        $this->identityProvider->add($id = 'abc');

        $shouldStartAt = Carbon::instance($this->dateProvider->current())->addHours(5)
            ->setSecond(0)
            ->setMicros(0);

        $reservation = $this->addReservation->execute(
            $boats = [$s1->id() => $qty = 2],
            $name = 'Tabarly',
            $numberHours = 3,
            $shouldStartAt->format('Y-m-d H:i'),
            $isInstructor = false,
            $isMember = false,
            'note'
        );

        $reservationExpected = new Reservation(
            new Id('abc'),
            new BoatTripDuration(shouldStartAt: $shouldStartAt, numberHours: $numberHours),
            new Sailor(name:$name, isInstructor: $isInstructor, isMember: $isMember),
            new BoatsCollection($boats),
            'note'
        );
        self::assertEquals($reservationExpected, $reservation);
    }

    /**
     * @test
     */
    public function shouldNotCreateReservationWhenNotBoatsAvailable()
    {
        $s1 = new Fleet(new Id('abc'), 25);
        $this->fleetRepository->save($s1->getState());
        $this->identityProvider->add($id = 'abc');

        $shouldStartAt = Carbon::instance($this->dateProvider->current())->addHours(5)
            ->setSecond(0)
            ->setMicros(0);

        $reservation = $this->addReservation->execute(
            $boats = [$s1->id() => $qty = 2],
            $name = 'Tabarly',
            $numberHours = 3,
            $shouldStartAt->format('Y-m-d H:i'),
            $isInstructor = false,
            $isMember = false,
            'note'
        );
        $this->reservationRepository->save($reservation);

        $this->expectBoatNotAvailable();
    }

    private function expectBoatNotAvailable(): void
    {
        self::expectException(BoatNotAvailable::class);
        self::expectExceptionMessage('error.boat_not_available');
    }
}
