<?php


namespace App\Signing\Signing\Domain\Entities\Vo;


use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;

class BoatTripDuration
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

    private function isEnded()
    {
        return $this->end !== null;
    }

    public function toArray():array
    {
        return [
            'start_at' => $this->start,
            'end_at' => $this->end,
            'number_hours' => $this-> numberHours
        ];
    }
}
