<?php


namespace App\Signing\Signing\Domain\Entities\Builder;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Entities\BoatTripDuration;

class BoatTripBuilder
{
    private DateProvider $dateProvider;
    private BoatsCollection $boats;
    private Sailor $sailor;
    private BoatTripDuration $boatTripDuration;
    private bool $isReservation = false;
    private ?string $note = null;

    private function __construct(private string $id)
    {
        $this->dateProvider = app(DateProvider::class);
        $this->boats = new BoatsCollection([]);
    }

    public static function build(string $id):self
    {
        return new BoatTripBuilder($id);
    }

    public function withBoats(array $boats):self
    {
        $this->boats = new BoatsCollection($boats);
        return $this;
    }

    public function withSailor(?string $memberId = null, ?string $name = '', ?bool $isInstructor = null, bool $isMember = null):self
    {
        $this->sailor = new Sailor($memberId, $name, $isInstructor, $isMember);
        return $this;
    }

    public function withDates(?\DateTime $shouldStartAt = null, ?\DateTime $startAt = null, ?\DateTime $endAt = null, ?float $numberHours = null):self
    {
        $this->boatTripDuration = new BoatTripDuration($shouldStartAt, $startAt, $numberHours, $endAt);
        return $this;
    }

    public function inProgress(?float $numberHours):BoatTrip
    {
        $boatTripDuration = $this->boatTripDuration ?? new BoatTripDuration(start: $this->dateProvider->current(), numberHours: $numberHours);
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats, $this->isReservation, $this->note);
    }

    public function notStarted(?\DateTime $shouldStartAt, ?float $numberHours):BoatTrip
    {
        $boatTripDuration = $this->boatTripDuration ?? new BoatTripDuration(shouldStartAt: $shouldStartAt, numberHours: $numberHours);
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats, $this->isReservation, $this->note);
    }

    public function ended(?float $numberHours):BoatTrip
    {
        $boatTripDuration = $this->boatTripDuration ?? new BoatTripDuration(start: $this->dateProvider->current(), numberHours: $numberHours, end: $this->dateProvider->current());
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats, $this->isReservation, $this->note);
    }

    public function fromState(?float $numberHours, ?\DateTime $startAt, ?\DateTime $endAt = null, ?\DateTime $shouldStartAt = null):BoatTrip
    {
        $boatTripDuration = $this->boatTripDuration ?? new BoatTripDuration($shouldStartAt, $startAt, $numberHours, $endAt);
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats, $this->isReservation, $this->note);
    }

    public function reservation(bool $reservation):self
    {
        $this->isReservation = $reservation;
        return $this;
    }

    public function withNote(string $note = null):self
    {
        $this->note = $note;
        return $this;
    }

    public function get():BoatTrip
    {
        return new BoatTrip(new Id($this->id), $this->boatTripDuration, $this->sailor, $this->boats, $this->isReservation, $this->note);
    }
}
