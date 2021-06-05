<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;

class FleetState implements State
{
    public function __construct(
        private string $id,
        private int $totalAvailable,
        private string $state,
        private array $rents = [],
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

    public function rentalRate(): array
    {
        return $this->rents;
    }

    public function toDomain()
    {
        return new Fleet(new Id($this->id), $this->totalAvailable, $this->state, $this->rents);
    }
}
