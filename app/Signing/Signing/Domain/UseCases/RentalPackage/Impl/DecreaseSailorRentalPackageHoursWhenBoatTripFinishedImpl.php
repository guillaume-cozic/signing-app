<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\DecreaseSailorRentalPackageHoursWhenBoatTripFinished;

class DecreaseSailorRentalPackageHoursWhenBoatTripFinishedImpl implements DecreaseSailorRentalPackageHoursWhenBoatTripFinished
{
    public function __construct(
        private BoatTripRepository $boatTripRepository
    ){}

    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);

    }
}
