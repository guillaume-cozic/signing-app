<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Shared\Entities\Entity;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\HasState;
use App\Signing\Signing\Domain\Exceptions\RentalPackageValidityNegative;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use Carbon\Carbon;

class RentalPackage extends Entity implements HasState
{
    /**
     * @throws RentalPackageValidityNegative
     */
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

    /**
     * @throws RentalPackageValidityNegative
     */
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

    public function createSailorRentalPackage(string $sailorRentalPackageId, string $sailorName, float $hours)
    {
        $now = Carbon::instance(app(DateProvider::class)->current());
        (new SailorRentalPackage(
            $sailorRentalPackageId,
            $sailorName,
            $this->id,
            $now->addDays($this->validityInDays),
            $hours
        ))->create();
    }

    public function reloadRentalPackage(SailorRentalPackage $sailorRentalPackage, float $hours)
    {
        $now = Carbon::instance(app(DateProvider::class)->current());
        $endValidity = $now->addDays($this->validityInDays);
        $sailorRentalPackage->reload($hours, $endValidity);
    }

    public function validityEndAtFromNow():Carbon
    {
        $now = Carbon::instance(app(DateProvider::class)->current());
        return $now->addDays($this->validityInDays);
    }
}
