<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Entities\State\BoatTripDurationState;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;

class BoatTripDuration implements HasState
{
    public function __construct(
        private \DateTime $start,
        private ?float $numberHours = null,
        private ?\DateTime $end = null
    ){}

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

    public function delayStart(int $minutes)
    {
        if($minutes < 0) throw new TimeCantBeNegative();
        if($this->isEnded()) throw new BoatTripAlreadyEnded();
        $this->start->add(\DateInterval::createFromDateString('+'.$minutes.' minutes'));
    }

    public function isEnded()
    {
        return $this->end !== null;
    }

    public function getState(): BoatTripDurationState
    {
        return new BoatTripDurationState($this->start, $this->numberHours, $this->end);
    }
}
