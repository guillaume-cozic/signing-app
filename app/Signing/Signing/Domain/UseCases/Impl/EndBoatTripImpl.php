<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;

class EndBoatTripImpl implements EndBoatTrip
{
    public function __construct(
        private BoatTripRepository $boatTripRepository,
        private DateProvider $dateProvider,
    ){}

    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        $boatTrip->end($this->dateProvider->current());
    }
}
