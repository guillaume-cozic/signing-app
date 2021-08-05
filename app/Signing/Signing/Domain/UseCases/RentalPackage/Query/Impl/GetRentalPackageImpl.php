<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Query\Impl;


use App\Signing\Signing\Application\ViewModel\RentalPackageViewModel;
use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;

class GetRentalPackageImpl implements GetRentalPackages
{
    public function __construct(
        private ReadRentalPackageRepository $readRentalPackageRepository,
        private ReadFleetRepository $readFleetRepository
    ){}

    public function execute(): array
    {
        $rentalPackages = $this->readRentalPackageRepository->all();
        foreach($rentalPackages as $rentalPackage){
            $fleetsName = [];
            foreach($rentalPackage->fleets() as $fleetId){
                $fleet = $this->readFleetRepository->getById($fleetId);
                $fleetsName[] = $fleet->name;
            }
            $rentalPackageViewModels[] = new RentalPackageViewModel($rentalPackage->id(), $rentalPackage->name(), $fleetsName, $rentalPackage->validity());
        }
        return $rentalPackageViewModels ?? [];
    }
}
