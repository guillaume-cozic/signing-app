<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetState;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use Illuminate\Support\Facades\App;

class InMemoryFleetRepository implements FleetRepository
{
    public function __construct(private array $fleets = []){}

    public function get(string $id): ?Fleet
    {
        return isset($this->fleets[$id]) ? $this->fleets[$id]->toDomain() : null ;
    }

    public function save(FleetState $s): void
    {
        $this->fleets[$s->id()] = $s;
    }

    public function all()
    {
        return $this->fleets;
    }

    public function getByName(string $name): ?Fleet
    {
        foreach($this->fleets as $fleet) {
            if($fleet->translations()['name'][App::getLocale()] === $name){
                return $fleet->toDomain();
            }
        }
        return null;
    }
}
