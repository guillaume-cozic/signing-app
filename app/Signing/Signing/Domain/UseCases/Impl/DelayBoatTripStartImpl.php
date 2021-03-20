<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\DelayBoatTripStart;

class DelayBoatTripStartImpl implements DelayBoatTripStart
{
    public function __construct(private BoatTripRepository $boatTripRepository){}

    public function execute(string $id, int $minutes)
    {
        $boatTrip = $this->boatTripRepository->get($id);

        $boatTrip->delayStart($minutes);
    }
}
