<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\EditRentalPackage;

class EditRentalPackageImpl implements EditRentalPackage
{
    public function __construct(
        private RentalPackageRepository $rentalPackageRepository
    ){}

    public function execute(string $rentalPackageId, array $fleets, string $name,  int $validityInDays = null)
    {
        $fleetsCollection = new FleetCollection($fleets);

        $rentalPackage = $this->rentalPackageRepository->get($rentalPackageId);
        $rentalPackage->update($name, $fleetsCollection, $validityInDays);
    }
}


