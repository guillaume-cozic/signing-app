<?php


namespace App\Events\BoatTrip;


class BoatTripStarted
{
    public function __construct(
        public string $boatTripId,
        public string $userId
    ){}
}
