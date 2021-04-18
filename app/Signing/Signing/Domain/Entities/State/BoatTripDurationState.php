<?php


namespace App\Signing\Signing\Domain\Entities\State;


use App\Signing\Signing\Domain\Entities\State;

class BoatTripDurationState implements State
{
    public function __construct(
        private ?\DateTime $start = null,
        private ?float $numberHours = null,
        private ?\DateTime $end = null,
        private ?\DateTime $shouldStartAt = null
    ){}

    public function startAt(): ?\DateTime
    {
        return $this->start;
    }

    public function shouldStartAt(): ?\DateTime
    {
        return $this->shouldStartAt;
    }

    public function numberHours(): ?float
    {
        return $this->numberHours;
    }

    public function endAt(): ?\DateTime
    {
        return $this->end;
    }
}
