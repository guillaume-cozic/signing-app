<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip;


interface CancelBoatTrip
{
    /**
     * @throws \App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded
     */
    public function execute(string $boatTripId);
}
