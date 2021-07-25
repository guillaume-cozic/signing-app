<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\State\BoatTripDurationState;
use App\Signing\Signing\Domain\Entities\State\SailorState;

class BoatTripState implements State
{
    public function __construct(
        private string $id,
        private BoatTripDurationState $duration,
        private array $boats,
        private SailorState $sailor
    ){}

    public function toBoatTrip():BoatTrip
    {
        return BoatTripBuilder::build($this->id)
            ->withBoats($this->boats)
            ->withSailor($this->memberId(), $this->name())
            ->fromState($this->numberHours(), $this->startAt(), $this->endAt(), $this->shouldStartAt());
    }

    public function hasBoat(string $boatIdAsked):bool
    {
        foreach ($this->boats() as $boatId => $qty) {
            if($boatIdAsked === $boatId){
                return true;
            }
        }
        return false;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function startAt(): ?\DateTime
    {
        return $this->duration->startAt();
    }

    public function shouldStartAt(): ?\DateTime
    {
        return $this->duration->shouldStartAt();
    }

    public function provisionalStartAt():\DateTime
    {
        return $this->duration->shouldStartAt() !== null ?  $this->duration->shouldStartAt() : $this->duration->startAt();
    }

    public function endAt(): ?\DateTime
    {
        return $this->duration->endAt();
    }

    public function numberHours(): ?float
    {
        return $this->duration->numberHours();
    }

    public function boats(): array
    {
        return $this->boats;
    }

    public function name(): ?string
    {
        return $this->sailor->name();
    }

    public function memberId(): ?string
    {
        return $this->sailor->memberId();
    }
}
