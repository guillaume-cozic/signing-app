<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\State;

class RentalPackageState implements State
{
    public function __construct(
        private string $id,
        private array $fleets,
        private string $name,
        private ?int $validityInDays = null
    ){
    }

    public function id():string
    {
        return $this->id;
    }

    public function toDomain():RentalPackage
    {
        return new RentalPackage($this->id, new FleetCollection($this->fleets), $this->name, $this->validityInDays);
    }
}
