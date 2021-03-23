<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\DisableFleet;

class DisableFleetImpl implements DisableFleet
{
    public function __construct(private FleetRepository $fleetRepository){}

    public function execute(string $fleetId)
    {
        $fleet = $this->fleetRepository->get($fleetId);
        $fleet->disable();
    }
}
