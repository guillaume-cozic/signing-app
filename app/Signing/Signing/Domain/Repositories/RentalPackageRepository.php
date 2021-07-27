<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;

interface RentalPackageRepository
{
    public function get(string $id):?RentalPackage;
    public function save(RentalPackage $r);
}
