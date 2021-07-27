<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;

use App\Signing\Signing\Domain\DomainServices\CreateBoatTripService;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;

class AddBoatTripImpl implements AddBoatTrip
{
    public function __construct(
        private CreateBoatTripService $createBoatTripService
    ){}

    /**
     * @throws BoatNotAvailable
     */
    public function execute(array $boats, string $name, float $numberHours, string $startAtHours = null, bool $startNow = null, ?bool $autoStart = false)
    {
        $this->createBoatTripService->execute(false, $boats,  $name, $numberHours, $startAtHours, $startNow, $autoStart);
    }
}
