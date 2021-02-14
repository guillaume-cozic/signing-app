<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\Repositories\SupportRepository;

class InMemorySupportRepository implements SupportRepository
{
    public function __construct(private array $supports = []){}

    public function get(string $id): ?Support
    {
        return isset($this->supports[$id]) ? clone $this->supports[$id] : null;
    }

    public function save(Support $s): void
    {
        $this->supports[$s->id()] = $s;
    }
}
