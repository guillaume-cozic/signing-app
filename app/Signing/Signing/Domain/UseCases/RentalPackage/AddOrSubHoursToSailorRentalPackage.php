<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage;


interface AddOrSubHoursToSailorRentalPackage
{
    public function execute(string $sailorRentalPackageId, float $hours);
}
