<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Exceptions\RentalPackageNotFound;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;

class CreateSailorRentalPackageImpl implements CreateSailorRentalPackage
{
    public function __construct(
        private RentalPackageRepository $rentalPackageRepository,
    ){}

    /**
     * @throws RentalPackageNotFound
     */
    public function execute(string $sailorRentalPackageId, string $rentalPackageId, ?string $name, float $hours, string $sailorId = null)
    {
        $rentalPackage = $this->checkIfRentalPackageExist($rentalPackageId);

        if($sailorId === null){
            $sailorId = (new Id())->id();
            (new Sailor(name:$name, sailorId: $sailorId))->create();
        }
        $sailor = new Sailor(name:$name, sailorId: $sailorId);
        $sailor->addRentalPackage($sailorRentalPackageId, $rentalPackage, $hours, $rentalPackage->validityEndAtFromNow());
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
