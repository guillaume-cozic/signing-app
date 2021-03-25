<?php


namespace App\Signing\Signing\Domain\UseCases\Query\Impl;


use App\Signing\Signing\Domain\Entities\Dto\FleetDto;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\Query\GetFleet;

class GetFleetImpl implements GetFleet
{
    public function __construct(private ReadFleetRepository $fleetRepository){}

    public function execute(string $fleeId): FleetDto
    {
        $fleet = $this->fleetRepository->getById($fleeId);
        if(!isset($fleet)) throw new FleetNotFound();
        return $fleet;
    }

}
