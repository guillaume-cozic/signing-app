<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackageState;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;

class InMemoryRentalPackageRepository implements RentalPackageRepository
{
    public function __construct(
        private array $rentalPackages = []
    ){}

    public function get(string $id): ?RentalPackage
    {
        return isset($this->rentalPackages[$id]) ? $this->rentalPackages[$id]?->toDomain() : null;
    }

    public function save(RentalPackageState $r)
    {
        $this->rentalPackages[$r->id()] = $r;
    }
}
