<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\State\BoatTripDurationState;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;

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

    public function end(\DateTime $endDate)
    {
        if($this->isEnded()) throw new BoatTripAlreadyEnded();
        $this->end = $endDate;
    }

    public function addTime(float $numberHours)
    {
        if($numberHours < 0) throw new TimeCantBeNegative();
        if($this->isEnded()) throw new BoatTripAlreadyEnded();
        $this->numberHours += $numberHours;
    }

    public function start()
    {
        $this->start = $this->dateProvider->current();
    }

    public function delayStart(int $minutes)
    {
        if($minutes < 0) throw new TimeCantBeNegative();
        if($this->isEnded()) throw new BoatTripAlreadyEnded();
        $this->start->add(\DateInterval::createFromDateString('+'.$minutes.' minutes'));
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
