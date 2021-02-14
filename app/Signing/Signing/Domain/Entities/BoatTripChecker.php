<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\SupportRepository;

class BoatTripChecker
{
    private SupportRepository $supportRepository;
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private string $supportId,
        private int $qtyAsked
    ){
        $this->supportRepository = app(SupportRepository::class);
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    /**
     * @throws \Exception
     */
    public function checkIfEnoughBoat():void
    {
        if (!$this->isSupportAvailable()) {
            throw new \Exception('support_not_available');
        }
    }

    private function isSupportAvailable():bool
    {
        $support = $this->supportRepository->get($this->supportId);
        $totalSupportUsed = $this->getTotalSupportUsed();
        return $totalSupportUsed + $this->qtyAsked < $support->totalAvailable();
    }

    private function getTotalSupportUsed(): int
    {
        $totalActive = 0;
        $boatTrips = $this->boatTripRepository->getBySupport($this->supportId);
        foreach ($boatTrips as $activeBoatTrip) {
            $totalActive += $activeBoatTrip->quantity();
        }
        return $totalActive;
    }
}
