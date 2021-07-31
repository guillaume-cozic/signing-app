<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\State\SailorState;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;

class Sailor implements HasState
{
    public function __construct(
        private ?string $memberId = '',
        private ?string $name = ''
    ){}

    public function decreaseHoursRentalPackage(RentalPackage $rentalPackage, float $hours)
    {
        $sailorRentalPackage = app(SailorRentalPackageRepository::class)->getByNameAndRentalPackage($this->name, $rentalPackage->id());
        if(isset($sailorRentalPackage)) {
            $sailorRentalPackage->decreaseHours($hours);
        }
    }

    public function getState(): SailorState
    {
        return new SailorState($this->name, $this->memberId);
    }
}
