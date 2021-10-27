<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\IdentityFleetParameters;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\DisableFleet;

class DisableFleetImpl implements DisableFleet, UseCase
{
    public function __construct(private FleetRepository $fleetRepository){}

    public function execute(string $fleetId)
    {
        $fleet = $this->fleetRepository->get($fleetId);
        $fleet->disable();
    }

    public function handle(IdentityFleetParameters|Parameters $parameters)
    {
        $this->execute($parameters->id);
    }
}
