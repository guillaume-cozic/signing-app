<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Signing\Domain\Entities\State;
use Carbon\Carbon;

class SailorRentalPackageState implements State
{
    public function __construct(
        private string $id,
        private string $name,
        private string $rentalPackageId,
        private Carbon $endValidity,
        private float $hours
    ){}

    public function name():string
    {
        return $this->name;
    }

    public function id():string
    {
        return $this->id;
    }

    public function rentalPackageId():string
    {
        return $this->rentalPackageId;
    }

    public function toDomain():SailorRentalPackage
    {
        return new SailorRentalPackage($this->id, $this->name, $this->rentalPackageId, $this->endValidity, $this->hours);
    }
}
