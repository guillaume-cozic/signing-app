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

    public function withSailor(?string $memberId = '', ?string $name = ''):self
    {
        $this->sailor = new Sailor($memberId, $name);
        return $this;
    }

    public function inProgress(?float $numberHours):BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats);
    }

    public function ended(?float $numberHours):BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours, $this->dateProvider->current());
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats);
    }

    public function fromState(?float $numberHours, ?\DateTime $startAt, ?\DateTime $endAt)
    {
        $boatTripDuration = new BoatTripDuration($startAt, $numberHours, $endAt);
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->sailor, $this->boats);
    }
}
