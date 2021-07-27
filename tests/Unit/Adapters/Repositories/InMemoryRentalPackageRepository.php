<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;

class InMemoryRentalPackageRepository implements RentalPackageRepository
{
    public function __construct(
        private array $rentalPackages = []
    ){}

    public function get(string $id): ?RentalPackage
    {
        return isset($this->rentalPackages[$id]) ? clone $this->rentalPackages[$id] : null;
    }

    public function save(RentalPackage $r)
    {
        $this->rentalPackages[$r->id()] = $r;
    }
}
