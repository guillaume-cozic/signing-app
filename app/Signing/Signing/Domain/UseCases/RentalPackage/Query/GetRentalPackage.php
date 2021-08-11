<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Query;


use App\Signing\Signing\Application\ViewModel\RentalPackageEditViewModel;

interface GetRentalPackage
{
    public function execute(string $rentalPackageId):?RentalPackageEditViewModel;
}
