<?php


namespace App\Signing\Signing\Domain\Entities;


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
}
