<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\AddSubHoursSailorRentalPackageParameters;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\DecreaseSailorRentalPackageHoursWhenBoatTripFinished;

class DecreaseSailorRentalPackageHoursWhenBoatTripFinishedImpl implements DecreaseSailorRentalPackageHoursWhenBoatTripFinished, UseCase
{
    public function __construct(
        private BoatTripRepository $boatTripRepository
    ){}

    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        $boatTrip->updateSailorRentalPackage();
    }

    public function handle(AddSubHoursSailorRentalPackageParameters|Parameters $parameters)
    {
        $this->execute($parameters->id);
    }
}
