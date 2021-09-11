<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage;


use App\Signing\Signing\Domain\Exceptions\RentalPackageNotFound;

interface CreateSailorRentalPackage
{
    /**
     * @throws RentalPackageNotFound
     */
    public function execute(
        string $sailorRentalPackageId,
        string $rentalPackageId,
        ?string $name,
        float $hours,
        string $sailorId = null
    );
}
