<?php

namespace App\Signing\Signing\Domain\Repositories;

use App\Signing\Signing\Domain\Entities\BoatTrip\Reservation;

interface ReservationRepository
{
    public function save();
    public function get():? Reservation;
}
