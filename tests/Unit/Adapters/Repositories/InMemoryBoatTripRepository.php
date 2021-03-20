<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;

class InMemoryBoatTripRepository implements BoatTripRepository
{
    public function __construct(private array $boatTrips = []){}

    public function get(string $id): ?BoatTrip
    {
        return isset($this->boatTrips[$id]) ? clone $this->boatTrips[$id] : null;
    }

    public function add(BoatTrip $b)
    {
        $this->boatTrips[$b->id()] = $b;
    }

    public function getInProgressByBoat(string $boatId): array
    {
        foreach($this->boatTrips as $boatTrip){
            if($boatTrip->hasBoat($boatId)) {
                $boatTrips[] = $boatTrip;
            }
        }
        return $boatTrips ?? [];
    }
}
