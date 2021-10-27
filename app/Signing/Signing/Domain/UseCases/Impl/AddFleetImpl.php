<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\AddFleetParameters;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\UseCases\AddFleet;

class AddFleetImpl implements AddFleet, UseCase
{
    /**
     * @throws FleetAlreadyExist
     * @throws NumberBoatsCantBeNegative
     */
    public function execute(string $title, string $description, int $totalAvailable, string $state)
    {
        (new Fleet(new Id(), $totalAvailable, $state))->create($title, $description);
    }

    public function handle(AddFleetParameters|Parameters $parameters)
    {
        $this->execute($parameters->name, '', $parameters->totalAvailable, $parameters->state);
    }


}
