<?php


namespace App\Signing\Signing\Domain\Repositories\Read;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackageState;

interface ReadRentalPackageRepository
{
    public function all();
    public function get(string $rentalPackageId):?RentalPackageState;
}
