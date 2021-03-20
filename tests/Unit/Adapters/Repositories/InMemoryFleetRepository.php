<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Repositories\FleetRepository;

class InMemoryFleetRepository implements FleetRepository
{
    public function __construct(private array $fleets = []){}

    public function get(string $id): ?Fleet
    {
        return isset($this->fleets[$id]) ? clone $this->fleets[$id] : null;
    }

    public function save(Fleet $s): void
    {
        $this->fleets[$s->id()] = $s;
    }

    public function all()
    {
        return $this->fleets;
    }
}
