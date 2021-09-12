<?php

namespace App\Signing\Signing\Domain\Entities\BoatTrip;

use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Sailor;

class Reservation
{
    public function __construct(
        private Id $id,
        private BoatTripDuration $duration,
        private Sailor $sailor,
        private ?BoatsCollection $boats = null,
        private ?string $note = null
    ){

    }
}
