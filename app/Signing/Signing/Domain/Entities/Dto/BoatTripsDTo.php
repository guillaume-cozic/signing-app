<?php


namespace App\Signing\Signing\Domain\Entities\Dto;


class BoatTripsDTo
{
    public function __construct(
        public string $id,
        public ?\DateTime $startAt = null,
        public ?\DateTime $endAt = null,
        public string $name = '',
        public ?array $boats = [],
        public float $hours = 0,
        public ?\DateTime $shouldStartAt = null
    ){}

    public function startAt():?\DateTime
    {
        return $this->startAt;
    }
}
