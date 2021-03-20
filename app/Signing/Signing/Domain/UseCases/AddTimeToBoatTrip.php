<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddTimeToBoatTrip
{
    public function execute(string $boatTripId, float $hours);
}
