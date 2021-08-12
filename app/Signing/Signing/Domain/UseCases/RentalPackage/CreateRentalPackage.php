<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage;


interface CreateRentalPackage
{
    public function execute(string $rentalPackageId, array $fleets, string $name, int $validityInDays = null);
}
