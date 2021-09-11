<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;

class FleetState implements State
{
    public function __construct(
        private string $id,
        private int $totalAvailable,
        private string $state,
        private array $translations = [],
    ){}

    public function id(): string
    {
        return $this->id;
    }

    public function totalAvailable(): int
    {
        return $this->totalAvailable;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function translations():array
    {
        return $this->translations;
    }

    /**
     * @throws NumberBoatsCantBeNegative
     */
    public function toDomain():Fleet
    {
        return new Fleet(new Id($this->id), $this->totalAvailable, $this->state);
    }
}
