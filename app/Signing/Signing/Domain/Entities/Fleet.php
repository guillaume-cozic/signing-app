<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use JetBrains\PhpStorm\Pure;

class Fleet implements HasState
{
    private FleetRepository $fleetRepository;

    public function __construct(
        private Id $id,
        private int $totalAvailable
    )
    {
        if($this->totalAvailable < 0) throw new NumberBoatsCantBeNegative('error.qty_can_not_be_lt_0');
        $this->fleetRepository = app(FleetRepository::class);
    }

    #[Pure] public function id():string
    {
        return $this->id->id();
    }

    public function create()
    {
        $this->fleetRepository->save($this);
    }

    public function isBoatAvailable(int $qtyAsked):bool
    {
        return $this->totalAvailable >= $qtyAsked;
    }

    public function getState(): FleetState
    {
        return new FleetState($this->id->id(), $this->totalAvailable);
    }
}
