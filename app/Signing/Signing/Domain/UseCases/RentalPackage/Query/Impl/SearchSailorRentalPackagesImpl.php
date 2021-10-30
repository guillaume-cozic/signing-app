<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Query\Impl;


use App\Signing\Signing\Application\ViewModel\SailorRentalPackageViewModel;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadSailorRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\SearchSailorRentalPackages;

class SearchSailorRentalPackagesImpl implements SearchSailorRentalPackages
{
    public function __construct(
        private ReadSailorRentalPackageRepository $readSailorRentalPackageRepository,
        private ReadRentalPackageRepository $readRentalPackageRepository,
    ){}

    public function execute(
        ?string $search = '',
        int $start = 0,
        int $perPage = 10,
        string $sort = null,
        string $dirSort = "asc",
        array $filters = []
    )
    {
        $sailorRentalPackages = $this->readSailorRentalPackageRepository->search($search, $start, $perPage, $sort, $dirSort, $filters);
        return $sailorRentalPackages->through(function (SailorRentalPackageState $sailorRentalPackage) {
            $rentalPackage = $this->readRentalPackageRepository->get($sailorRentalPackage->rentalPackageId());
            return new SailorRentalPackageViewModel(
                $sailorRentalPackage->id(),
                $sailorRentalPackage->sailorId(),
                $rentalPackage->name(),
                $sailorRentalPackage->hours()
            );
        });
    }
}
