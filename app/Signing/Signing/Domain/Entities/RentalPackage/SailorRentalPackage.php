<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Signing\Domain\Entities\HasState;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use Carbon\Carbon;

class SailorRentalPackage implements HasState
{
    public function __construct(
        private string $id,
        private string $name,
        private string $rentalPackageId,
        private Carbon $endValidity,
        private float $hours,
    ){}

    public function id():string
    {
        return $this->id;
    }

    public function reload(float $hours, Carbon $endValidity)
    {
        $this->hours += $hours;
        $this->endValidity = $endValidity;
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function create()
    {
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function decreaseHours(float $hours)
    {
        $this->hours -= $hours;
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function addOrSubHours(float $hours)
    {
        $this->hours += $hours;
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function getState(): SailorRentalPackageState
    {
        return new SailorRentalPackageState($this->id, $this->name, $this->rentalPackageId, $this->endValidity, $this->hours);
    }
}
