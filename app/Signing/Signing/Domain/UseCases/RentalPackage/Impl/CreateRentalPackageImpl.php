<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\RentalPackageParameters;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Exceptions\RentalPackageWithoutFleet;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateRentalPackage;

class CreateRentalPackageImpl implements CreateRentalPackage, UseCase
{
    public function execute(string $rentalPackageId, array $fleets, string $name, int $validityInDays = null)
    {
        $this->CheckIfFleetNotEmpty($fleets);
        (new RentalPackage($rentalPackageId, new FleetCollection($fleets), $name, $validityInDays))->save();
    }

    /**
     * @param array $fleets
     * @throws RentalPackageWithoutFleet
     */
    private function CheckIfFleetNotEmpty(array $fleets): void
    {
        if (empty($fleets)) {
            throw new RentalPackageWithoutFleet();
        }
    }

    public function handle(RentalPackageParameters|Parameters $parameters)
    {
        $this->execute($parameters->id, $parameters->fleets, $parameters->name, $parameters->days);

    }
}
