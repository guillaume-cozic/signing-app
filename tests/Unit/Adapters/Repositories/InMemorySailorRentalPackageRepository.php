<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;

class InMemorySailorRentalPackageRepository implements SailorRentalPackageRepository
{
    public function __construct(
        private $sailorRentalPackages = []
    ){}

    public function get(string $id): ?SailorRentalPackage
    {
        return isset($this->sailorRentalPackages[$id]) ? $this->sailorRentalPackages[$id]?->toDomain() : null;
    }

    public function save(SailorRentalPackageState $sailorRentalPackageState)
    {
        $this->sailorRentalPackages[$sailorRentalPackageState->id()] = $sailorRentalPackageState;
    }

    public function getByNameAndRentalPackage(string $name, string $packageRentalId): ?SailorRentalPackage
    {
        foreach($this->sailorRentalPackages as $sailorRentalPackage) {
            if($sailorRentalPackage->rentalPackageId() === $packageRentalId && $sailorRentalPackage->name() === $name){
                return $sailorRentalPackage->toDomain();
            }
        }
        return null;
    }


    public function getBySailorAndRentalPackage(Sailor $sailor, RentalPackage $rentalPackage): ?SailorRentalPackage
    {
        foreach($this->sailorRentalPackages as $sailorRentalPackage) {
            if($sailorRentalPackage->rentalPackageId() === $rentalPackage->id() && $sailorRentalPackage->sailorId() === $sailor->id()){
                return $sailorRentalPackage->toDomain();
            }
        }
        return null;
    }


}
