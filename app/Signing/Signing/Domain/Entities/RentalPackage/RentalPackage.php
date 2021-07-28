<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\HasState;
use App\Signing\Signing\Domain\Exceptions\RentalPackageValidityNegative;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;

class RentalPackage implements HasState
{
    public function __construct(
        private string $id,
        private FleetCollection $fleets,
        private string $name,
        private ?int $validityInDays = null
    ){
        $this->checkValidityInDaysPositive($validityInDays);
    }

    public function id():string
    {
        return $this->id;
    }

    public function save()
    {
        app(RentalPackageRepository::class)->save($this->getState());
    }

    public function update(string $name, FleetCollection $fleets, ?int $validityInDays)
    {
        $this->fleets = $fleets;
        $this->name = $name;
        $this->checkValidityInDaysPositive($validityInDays);
        $this->validityInDays = $validityInDays;
        app(RentalPackageRepository::class)->save($this->getState());
    }

    /**
     * @param int|null $validityInDays
     * @throws RentalPackageValidityNegative
     */
    private function checkValidityInDaysPositive(?int $validityInDays): void
    {
        if (isset($validityInDays) && $validityInDays <= 0) {
            throw new RentalPackageValidityNegative();
        }
    }

    public function getState(): RentalPackageState
    {
        return new RentalPackageState($this->id, $this->fleets->toArray(), $this->name, $this->validityInDays);
    }
}
