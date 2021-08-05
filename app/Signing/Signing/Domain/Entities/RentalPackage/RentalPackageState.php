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

    public function hasFleet(string $fleet):bool
    {
        return in_array($fleet, $this->fleets);
    }

    public function fleets():array
    {
        return $this->fleets;
    }

    public function validity():int
    {
        return $this->validityInDays;
    }

    public function name():string
    {
        return $this->name;
    }
}
