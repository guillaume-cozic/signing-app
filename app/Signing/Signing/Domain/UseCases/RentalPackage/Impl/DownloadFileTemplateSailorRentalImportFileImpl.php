<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\DownloadFileTemplateSailorRentalImportFile;

class DownloadFileTemplateSailorRentalImportFileImpl implements DownloadFileTemplateSailorRentalImportFile
{
    public function __construct(
        public ReadRentalPackageRepository $rentalPackageRepository
    ){}

    public function execute()
    {
        $rentalPackages = $this->rentalPackageRepository->all();

    }
}
