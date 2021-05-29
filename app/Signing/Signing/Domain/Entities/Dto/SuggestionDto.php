<?php


namespace App\Signing\Signing\Domain\Entities\Dto;


class SuggestionDto
{
    public function __construct(
        public string $action,
        public BoatTripsDTo $boatTrip
    ){}
}
