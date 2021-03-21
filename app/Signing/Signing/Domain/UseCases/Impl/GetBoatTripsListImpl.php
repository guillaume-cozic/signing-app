<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;

class GetBoatTripsListImpl implements GetBoatTripsList
{
    public function __construct(
        private ReadBoatTripRepository $boatTripRepository
    ){}

    public function execute()
    {
        return $this->boatTripRepository->getInProgress();
    }
}
