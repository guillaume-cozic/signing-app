<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\FleetRepository;

class BoatAvailabilityChecker
{
    private FleetRepository $fleetRepository;
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private BoatsCollection $boatsAsked
    ){
        $this->fleetRepository = app(FleetRepository::class);
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    /**
     * @throws BoatNotAvailable
     */
    public function checkIfEnoughBoat():void
    {
        foreach($this->boatsAsked->boats() as $boatId => $qty) {
            if (!$this->isSupportAvailable($boatId, $qty)) throw new BoatNotAvailable('error.support_not_available');
        }
    }

    private function isSupportAvailable(string $boatId, int $qty):bool
    {
        $support = $this->fleetRepository->get($boatId);
        $totalSupportUsed = $this->getTotalSupportUsed($boatId);
        return $support->isBoatAvailable($totalSupportUsed + $qty);
    }

    private function getTotalSupportUsed(string $boatId): int
    {
        $totalActive = 0;
        $boatTrips = $this->boatTripRepository->getBySupport($boatId);
        foreach ($boatTrips as $activeBoatTrip) {
            $totalActive += $activeBoatTrip->quantity($boatId);
        }
        return $totalActive;
    }
}
