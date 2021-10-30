<?php

namespace Tests\Unit\Adapters\Repositories;

use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Entities\State\SailorState;
use App\Signing\Signing\Domain\Repositories\SailorRepository;

class InMemorySailorRepository implements SailorRepository
{
    private array $sailors = [];

    public function getByName(string $name):? Sailor
    {
        foreach($this->sailors as $sailor){
            if($sailor->name() === $name){
                return $sailor->toDomain();
            }
        }
        return null;
    }

    public function save(SailorState $sailor)
    {
        $this->sailors[] = $sailor;
    }
}
