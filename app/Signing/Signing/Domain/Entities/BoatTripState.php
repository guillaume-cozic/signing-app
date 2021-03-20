<?php


namespace App\Signing\Signing\Domain\Entities;


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

    public function id(): string
    {
        return $this->id;
    }

    public function startAt(): \DateTime
    {
        return $this->duration->startAt();
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
