<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;

class UpdateFleetImpl implements UpdateFleet
{
    public function __construct(private FleetRepository $fleetRepository){}

    public function execute(string $id, int $newTotal, string $title, string $state)
    {
        $fleet = $this->fleetRepository->get($id);
        if(!isset($fleet)) throw new FleetNotFound();
        $fleet->update($newTotal, $title, $state);
    }
}
