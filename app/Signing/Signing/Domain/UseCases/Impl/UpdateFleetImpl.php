<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\EditFleetParameters;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;

class UpdateFleetImpl implements UpdateFleet, UseCase
{
    public function __construct(private FleetRepository $fleetRepository){}

    /**
     * @throws FleetNotFound
     * @throws \App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist
     * @throws \App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative
     */
    public function execute(string $id, int $newTotal, string $title, string $state)
    {
        $fleet = $this->fleetRepository->get($id);
        if(!isset($fleet)) throw new FleetNotFound();

        $fleet->update($newTotal, $title, $state);
    }

    public function handle(EditFleetParameters|Parameters $parameters)
    {
        $this->execute($parameters->id, $parameters->totalAvailable, $parameters->name, $parameters->state);
    }
}
