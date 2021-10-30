<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\Entities\Sailor;

interface SailorRentalPackageRepository
{
    public function get(string $id):?SailorRentalPackage;
    public function getByNameAndRentalPackage(string $name, string $packageRentalId):?SailorRentalPackage;
    public function getBySailorAndRentalPackage(Sailor $sailor, RentalPackage $rentalPackage):?SailorRentalPackage;
    public function save(SailorRentalPackageState $sailorRentalPackageState);
}
