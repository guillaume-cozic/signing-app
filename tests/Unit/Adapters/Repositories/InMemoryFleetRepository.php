<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Repositories\FleetRepository;

class InMemoryFleetRepository implements FleetRepository
{
    public function __construct(private array $supports = []){}

    public function get(string $id): ?Fleet
    {
        return isset($this->supports[$id]) ? clone $this->supports[$id] : null;
    }

    public function save(Fleet $s): void
    {
        $this->supports[$s->id()] = $s;
    }
}
