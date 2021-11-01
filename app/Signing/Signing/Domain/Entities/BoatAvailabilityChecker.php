<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Entities\BoatTrip\BoatsCollection;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\FleetRepository;

class BoatAvailabilityChecker
{
    private FleetRepository $fleetRepository;
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private BoatsCollection $boatsAsked,
        private \DateTime $startAt,
        private float $hours
    ){
        $this->fleetRepository = app(FleetRepository::class);
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    /**
     * @throws BoatNotAvailable
     */
    public function checkIfEnough():void
    {
        foreach($this->boatsAsked->boats() as $boatId => $qty) {
            if (!$this->isBoatAvailable($boatId, $qty)) throw new BoatNotAvailable('error.boat_not_available');
        }
    }

    private function isBoatAvailable(string $boatId, int $qty):bool
    {
        $fleet = $this->fleetRepository->get($boatId);
        $totalSupportUsed = $this->getTotalBoatUsed($boatId);
        return $fleet->isBoatAvailable($totalSupportUsed + $qty);
    }

    private function getTotalBoatUsed(string $boatId): int
    {
        $totalActive = 0;
        $startAt = clone $this->startAt;
        $endAt = $startAt->add(new \DateInterval('PT'.($this->hours*60).'M'));
        $boatTrips = $this->boatTripRepository->getUsedBoat($boatId, $this->startAt, $endAt);
        foreach ($boatTrips as $activeBoatTrip) {
            $totalActive += $activeBoatTrip->quantity($boatId);
        }
        return $totalActive;
    }
}
