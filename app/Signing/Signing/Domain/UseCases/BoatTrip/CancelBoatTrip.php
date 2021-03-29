<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip;


interface CancelBoatTrip
{
    public function execute(string $boatTripId);
}
