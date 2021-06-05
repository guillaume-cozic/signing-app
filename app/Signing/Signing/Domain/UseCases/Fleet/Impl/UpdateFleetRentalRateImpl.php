<?php


namespace App\Signing\Signing\Domain\UseCases\Fleet\Impl;


use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\Fleet\UpdateFleetRentalRate;

class UpdateFleetRentalRateImpl implements UpdateFleetRentalRate
{
    public function __construct(
        private FleetRepository $fleetRepository
    ){}

    public function execute(string $id, array $rents)
    {
        $fleet = $this->fleetRepository->get($id);
        if(!isset($fleet)){
            throw new FleetNotFound();
        }
        $fleet->updateRentalRent($rents);
    }
}
