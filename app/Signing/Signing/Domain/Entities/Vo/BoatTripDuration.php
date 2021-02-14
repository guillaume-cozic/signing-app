<?php


namespace App\Signing\Signing\Domain\Entities\Vo;


class BoatTripDuration
{
    public function __construct(
        private \DateTime $start,
        private ?int $numberHours = null,
        private ?\DateTime $end = null
    ){}

    public function end(\DateTime $endDate)
    {
        $this->end = $endDate;
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
