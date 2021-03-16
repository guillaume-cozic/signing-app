<?php


namespace App\Signing\Signing\Domain\Entities\Dto;


class BoatTripsDTo
{
    public function __construct(
        public string $id,
        public \DateTime $startAt,
        public ?\DateTime $endAt,
        public ?string $supportName,
        public string $numberHours
    ){}

}
