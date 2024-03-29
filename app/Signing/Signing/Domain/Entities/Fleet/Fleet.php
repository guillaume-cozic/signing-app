<?php


namespace App\Signing\Signing\Domain\Entities\Fleet;


use App\Signing\Shared\Entities\HasState;
use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\Pure;

class Fleet implements HasState
{
    private FleetRepository $fleetRepository;
    private array $translations = [];

    const STATE_ACTIVE = 'active';
    const STATE_INACTIVE = 'inactive';

    public function __construct(
        private Id $id,
        private int $totalAvailable,
        private string $state = self::STATE_ACTIVE,
    )
    {
        if($this->totalAvailable < 0) throw new NumberBoatsCantBeNegative('error.qty_can_not_be_lt_0');
        $this->fleetRepository = app(FleetRepository::class);
    }

    #[Pure] public function id():string
    {
        return $this->id->id();
    }

    public function create(string $title)
    {
        $fleet = $this->fleetRepository->getByName($title);
        if($fleet !== null){
            throw new FleetAlreadyExist();
        }
        $this->translations = [
            'name' => [App::getLocale() => $title],
        ];
        $this->fleetRepository->save($this->getState());
    }

    public function update(int $totalAvailable, string $title, string $state)
    {
        if($totalAvailable < 0) throw new NumberBoatsCantBeNegative('error.qty_can_not_be_lt_0');

        $fleetCheck = $this->fleetRepository->getByName($title);
        if(isset($fleetCheck) && $this->id() !== $fleetCheck->id()){
            throw new FleetAlreadyExist();
        }
        $this->totalAvailable = $totalAvailable;
        $this->state = $state;
        $this->fleetRepository->save($this->getState());
    }

    public function disable()
    {
        $this->state = self::STATE_INACTIVE;
        $this->fleetRepository->save($this->getState());
    }

    public function enable()
    {
        $this->state = self::STATE_ACTIVE;
        $this->fleetRepository->save($this->getState());
    }

    public function isBoatAvailable(int $qtyAsked):bool
    {
        return $this->totalAvailable >= $qtyAsked;
    }

    public function getState(): FleetState
    {
        return new FleetState($this->id->id(), $this->totalAvailable, $this->state, $this->translations);
    }
}
