<?php


namespace App\Events\BoatTrip;


class BoatTripEnded
{
    public function __construct(
        public string $boatTripId,
        public string $userId
    ){}
}
