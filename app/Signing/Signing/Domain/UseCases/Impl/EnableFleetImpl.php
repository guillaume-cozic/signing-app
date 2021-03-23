<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\EnableFleet;

class EnableFleetImpl implements EnableFleet
{
    public function __construct(private FleetRepository $fleetRepository){}

    public function execute(string $fleetId)
    {
        $fleet = $this->fleetRepository->get($fleetId);
        $fleet->enable();
    }
}
