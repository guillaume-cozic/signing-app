<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\AddFleet;

class AddFleetImpl implements AddFleet
{
    public function __construct(
        private FleetRepository $fleetRepository
    ){}

    /**
     * @throws FleetAlreadyExist
     * @throws NumberBoatsCantBeNegative
     */
    public function execute(string $title, string $description, int $totalAvailable, string $state)
    {
        $fleet = $this->fleetRepository->getByName($title);
        if($fleet !== null){
            throw new FleetAlreadyExist();
        }
        (new Fleet(new Id(), $totalAvailable, $state))->create($title, $description);
    }
}
