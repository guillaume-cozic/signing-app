<?php


namespace App\Signing\Signing\Domain\Events\BoatTrip;

class BoatTripStarted
{
    public function __construct(
        public string $boatTripId,
        public string $userId
    ){}
}
