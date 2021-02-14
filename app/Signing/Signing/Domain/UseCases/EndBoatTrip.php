<?php


namespace App\Signing\Signing\Domain\UseCases;


interface EndBoatTrip
{
    public function execute(string $boatTripId);
}
