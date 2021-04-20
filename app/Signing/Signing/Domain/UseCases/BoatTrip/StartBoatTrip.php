<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip;


interface StartBoatTrip
{
    public function execute(string $boatTripId);
}
