<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use App\Signing\Shared\Providers\DateProvider;
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
        private array $actions = [],
    ){}

    public function id():string
    {
        return $this->id;
    }

    public function reload(float $hours, Carbon $endValidity)
    {
        $this->hours += $hours;
        $this->endValidity = $endValidity;
        $this->addAction(ActionSailor::ADD_HOURS, $hours);
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    private function addAction(string $type, float $hours)
    {
        $this->actions[] = new ActionSailor($type, $hours, Carbon::instance(app(DateProvider::class)->current()));
    }

    public function create()
    {
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function decreaseHours(float $hours)
    {
        $this->hours -= $hours;
        $this->addAction(ActionSailor::SAIL_HOURS, $hours);
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function addOrSubHours(float $hours)
    {
        $this->hours += $hours;
        $type = $hours < 0 ? ActionSailor::SUB_HOURS : ActionSailor::ADD_HOURS;
        $this->addAction($type, $hours);
        app(SailorRentalPackageRepository::class)->save($this->getState());
    }

    public function getState(): SailorRentalPackageState
    {
        return new SailorRentalPackageState($this->id, $this->name, $this->rentalPackageId, $this->endValidity, $this->hours, $this->actions);
    }
}
