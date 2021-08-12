<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Exceptions\RentalPackageNotFound;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;

class CreateSailorRentalPackageImpl implements CreateSailorRentalPackage
{
    public function __construct(
        private RentalPackageRepository $rentalPackageRepository,
        private SailorRentalPackageRepository $sailorRentalPackageRepository
    ){}

    /**
     * @throws RentalPackageNotFound
     */
    public function execute(string $sailorRentalPackageId, string $rentalPackageId, string $name, float $hours)
    {
        $rentalPackage = $this->checkIfRentalPackageExist($rentalPackageId);

        $sailorRentalPackage = $this->sailorRentalPackageRepository->getByNameAndRentalPackage($name, $rentalPackageId);
        if(isset($sailorRentalPackage)){
            $rentalPackage->reloadRentalPackage($sailorRentalPackage, $hours);
            return;
        }

        $rentalPackage->createSailorRentalPackage($sailorRentalPackageId, $name, $hours);
    }

    /**
     * @throws RentalPackageNotFound
     */
    private function checkIfRentalPackageExist(string $rentalPackageId):RentalPackage
    {
        $rentalPackage = $this->rentalPackageRepository->get($rentalPackageId);
        if (!isset($rentalPackage)) {
            throw new RentalPackageNotFound();
        }
        return $rentalPackage;
    }
}
