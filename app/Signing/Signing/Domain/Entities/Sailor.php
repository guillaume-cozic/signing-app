<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Entity;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\State\SailorState;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\SailorRepository;
use Carbon\Carbon;

class Sailor extends Entity implements HasState
{
    private SailorRentalPackageRepository $sailorRentalPackageRepository;
    private SailorRepository $sailorRepository;

    public function __construct(
        private ?string $memberId = '',
        private ?string $name = '',
        private ?bool $isInstructor = null,
        private ?bool $isMember = null,
        private ?string $sailorId = null,
    ){
        $this->sailorRentalPackageRepository = app(SailorRentalPackageRepository::class);
        $this->sailorRepository = app(SailorRepository::class);
    }

    public function id():?string
    {
        return $this->sailorId;
    }

    public function decreaseHoursRentalPackage(RentalPackage $rentalPackage, float $hours)
    {
        $sailorRentalPackage = $this->sailorRentalPackageRepository->getBySailorAndRentalPackage($this, $rentalPackage);
        if(isset($sailorRentalPackage)) {
            $sailorRentalPackage->decreaseHours($hours);
        }
    }

    public function create()
    {
        $this->sailorRepository->save($this->getState());
    }

    public function addRentalPackage(string $sailorRentalPackageId, RentalPackage $rentalPackage, float $hours, Carbon $validityEndAt)
    {
        $sailorRentalPackage = $this->sailorRentalPackageRepository->getBySailorAndRentalPackage($this, $rentalPackage);
        if(!isset($sailorRentalPackage)){
            (new SailorRentalPackage(
                $sailorRentalPackageId,
                $this->sailorId,
                $rentalPackage->id(),
                $validityEndAt,
                $hours
            ))->create();
            return;
        }
        $sailorRentalPackage->reload($hours, $validityEndAt);
    }

    public function getState(): SailorState
    {
        return new SailorState($this->name, $this->memberId, $this->isInstructor, $this->isMember, $this->sailorId);
    }
}
