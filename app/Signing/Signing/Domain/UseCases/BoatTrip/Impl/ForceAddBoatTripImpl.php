<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;

use App\Signing\Signing\Domain\DomainServices\CreateBoatTripService;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;

class ForceAddBoatTripImpl implements ForceAddBoatTrip
{
    public function __construct(
        private CreateBoatTripService $createBoatTripService
    ){}

    public function execute(
        array $boats,
        string $name,
        float $numberHours,
        string $startAtHours = null,
        bool $startNow = null,
        ?bool $autoStart = false,
        bool $isInstructor = false,
        bool $isMember = false,
        ?string $note = null,
        ?string $sailorId = null
    )
    {
        $this->createBoatTripService->execute(
            true,
            $boats,
            $name,
            $numberHours,
            $startAtHours,
            $startNow,
            $autoStart,
            $isInstructor,
            $isMember,
            $note,
            $sailorId
        );
    }
}
