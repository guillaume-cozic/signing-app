<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;

use App\Signing\Signing\Domain\DomainServices\CreateBoatTripService;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;

class ForceAddBoatTripImpl implements ForceAddBoatTrip
{
    public function __construct(
        private CreateBoatTripService $createBoatTripService
    ){}

    public function execute(array $boats, string $name, int $numberHours, string $startAtHours = null, bool $startNow = null, ?bool $startAuto = false)
    {
        $this->createBoatTripService->execute(true, $boats,  $name, $numberHours, $startAtHours, $startNow, $startAuto);
    }
}
