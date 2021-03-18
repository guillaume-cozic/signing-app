<?php


namespace App\Signing\Signing\Domain\Entities;


class BoatsCollection
{
    public function __construct(private array $boats){}

    public function quantity(string $boatId):int
    {
        return $this->boats[$boatId] ?? 0;
    }

    public function boats():array
    {
        return $this->boats;
    }
}
