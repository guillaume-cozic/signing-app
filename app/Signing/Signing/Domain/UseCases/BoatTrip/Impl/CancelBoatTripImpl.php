<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;


use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\BoatTrip\CancelBoatTrip;

class CancelBoatTripImpl implements CancelBoatTrip
{
    public function __construct(private BoatTripRepository $boatTripRepository){}

    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        $boatTrip->cancel();
    }
}
