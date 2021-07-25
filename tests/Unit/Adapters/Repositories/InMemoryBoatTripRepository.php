<?php


namespace Tests\Unit\Adapters\Repositories;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTripState;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;

class InMemoryBoatTripRepository implements BoatTripRepository
{
    public function __construct(private array $boatTrips = []){}

    public function get(string $id): ?BoatTrip
    {
        return isset($this->boatTrips[$id]) ? $this->boatTrips[$id]->toBoatTrip() : null;
    }

    public function save(BoatTripState $b)
    {
        $this->boatTrips[$b->id()] = $b;
    }

    public function getInProgressByBoat(string $boatId): array
    {
        foreach($this->boatTrips as $boatTrip){
            if($boatTrip->hasBoat($boatId)) {
                $boatTrips[] = $boatTrip->toBoatTrip();
            }
        }
        return $boatTrips ?? [];
    }

    public function getUsedBoat(string $boatId, \DateTime $startAt, \DateTime $provisionalEndDate): array
    {
        foreach($this->boatTrips as $boatTrip){
            $provisionalBoatTripStartAt = clone $boatTrip->provisionalStartAt();
            $boatTripProvisionalEndAt = $provisionalBoatTripStartAt->add(new \DateInterval('PT'.$boatTrip->numberHours().'H'));

            if(
                $boatTrip->hasBoat($boatId) && (
                    ($startAt >= $boatTrip->provisionalStartAt() && $startAt <= $boatTripProvisionalEndAt)
                    || ($provisionalEndDate >= $boatTrip->provisionalStartAt() && $provisionalEndDate <= $boatTripProvisionalEndAt)
                    || ($startAt <= $boatTrip->provisionalStartAt() && $provisionalEndDate >= $boatTripProvisionalEndAt)
                )
            ) {
                $boatTrips[] = $boatTrip->toBoatTrip();
            }
        }
        return $boatTrips ?? [];
    }


    public function delete(string $boatTripId)
    {
        unset($this->boatTrips[$boatTripId]);
    }
}
