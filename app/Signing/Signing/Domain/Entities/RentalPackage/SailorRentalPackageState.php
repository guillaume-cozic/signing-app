<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Signing\Domain\Entities\State;
use Carbon\Carbon;

class SailorRentalPackageState implements State
{
    public function __construct(
        private string $id,
        private string $sailorId,
        private string $rentalPackageId,
        private Carbon $endValidity,
        private float $hours,
        private array $actions = [],
    ){}

    public function sailorId():string
    {
        return $this->sailorId;
    }

    public function id():string
    {
        return $this->id;
    }

    public function rentalPackageId():string
    {
        return $this->rentalPackageId;
    }

    public function hours():float
    {
        return $this->hours;
    }

    public function endValidity():Carbon
    {
        return $this->endValidity;
    }

    public function actions():array
    {
        return $this->actions;
    }

    public function toDomain():SailorRentalPackage
    {
        return new SailorRentalPackage(
            $this->id,
            $this->sailorId,
            $this->rentalPackageId,
            $this->endValidity,
            $this->hours,
            $this->actions
        );
    }
}
