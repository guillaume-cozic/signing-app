<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;


use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\BoatTrip\CancelBoatTrip;

class CancelBoatTripImpl implements CancelBoatTrip
{
    public function __construct(private BoatTripRepository $boatTripRepository){}

    /**
     * @throws \App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded
     */
    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        if(!isset($boatTrip)){
            return;
        }
        $boatTrip->cancel();
    }
}
