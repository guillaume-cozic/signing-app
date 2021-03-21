<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;

class FleetState implements State
{
    public function __construct(private string $id, private int $totalAvailable){}

    public function id(): string
    {
        return $this->id;
    }

    public function totalAvailable(): int
    {
        return $this->totalAvailable;
    }

    public function toDomain()
    {
        return new Fleet(new Id($this->id), $this->totalAvailable);
    }
}
