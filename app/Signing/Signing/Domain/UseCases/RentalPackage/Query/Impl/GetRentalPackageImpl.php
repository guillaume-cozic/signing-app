<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Query\Impl;


use App\Signing\Signing\Application\ViewModel\RentalPackageEditViewModel;
use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackage;

class GetRentalPackageImpl implements GetRentalPackage
{
    public function __construct(
        private ReadRentalPackageRepository $readRentalPackageRepository
    ){}

    public function execute(string $rentalPackageId):?RentalPackageEditViewModel
    {
        $rentalPackage = $this->readRentalPackageRepository->get($rentalPackageId);
        if(!isset($rentalPackage)){
            return null;
        }
        return new RentalPackageEditViewModel($rentalPackage->id(), $rentalPackage->name(), $rentalPackage->fleets(), $rentalPackage->validity());
    }
}
