<?php


namespace App\Signing\Signing\Domain\Entities\BoatTrip;


use App\Signing\Shared\Entities\HasState;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\State\BoatTripDurationState;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;

class BoatTripDuration implements HasState
{
    private DateProvider $dateProvider;

    public function __construct(
        private ?\DateTime $shouldStartAt = null,
        private ?\DateTime $start = null,
        private ?float $numberHours = null,
        private ?\DateTime $end = null
    ){
        $this->dateProvider = app(DateProvider::class);
    }

    public function end(\DateTime $endDate):BoatTripDuration
    {
        if($this->isEnded()) throw new BoatTripAlreadyEnded();
        $this->start = $this->start ?? $this->shouldStartAt;
        return new BoatTripDuration($this->shouldStartAt, $this->start, $this->numberHours, $endDate);

    }

    public function start():BoatTripDuration
    {
        return new BoatTripDuration($this->shouldStartAt, $this->dateProvider->current(), $this->numberHours);
    }

    public function isEnded():bool
    {
        return $this->end !== null;
    }

    public function hours():?float
    {
        return $this->numberHours;
    }

    public function startAt():\DateTime
    {
        return $this->shouldStartAt ?? $this->start;
    }

    public function isStarted():bool
    {
        return $this->start !== null;
    }

    public function getState(): BoatTripDurationState
    {
        return new BoatTripDurationState($this->start, $this->numberHours, $this->end, $this->shouldStartAt);
    }
}
