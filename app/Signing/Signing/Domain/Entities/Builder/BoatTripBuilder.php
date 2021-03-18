<?php


namespace App\Signing\Signing\Domain\Entities\Builder;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;

class BoatTripBuilder
{
    private DateProvider $dateProvider;
    private BoatsCollection $boats;

    private function __construct(private string $id)
    {
        $this->dateProvider = app(DateProvider::class);
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

    public function inProgress(?float $numberHours, string $name = null, string $memberId = null):BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->boats, $name, $memberId);
    }

    public function ended(?float $numberHours, string $name = null, string $memberId = null):BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours, $this->dateProvider->current());
        return new BoatTrip(new Id($this->id), $boatTripDuration, $this->boats, $name, $memberId);
    }

}
