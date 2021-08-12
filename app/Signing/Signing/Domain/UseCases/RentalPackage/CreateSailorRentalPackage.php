<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage;


interface CreateSailorRentalPackage
{
    public function execute(string $sailorRentalPackageId, string $rentalPackageId, string $name, float $hours);
}
