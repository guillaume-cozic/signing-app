<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\AddTimeToBoatTrip;

class AddTimeToBoatTripImpl implements AddTimeToBoatTrip
{
    public function __construct(private BoatTripRepository $boatTripRepository){}

    public function execute(string $boatTripId, float $hours)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        $boatTrip->addTime($hours);
    }

}
