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
    public function execute(
        array $boats,
        string $name,
        float $numberHours,
        string $startAtHours = null,
        bool $startNow = null,
        ?bool $autoStart = false,
        bool $isInstructor = false,
        bool $isMember = false,
        bool $isReservation = false,
        ?string $note = null,
        ?string $sailorId = null
    )
    {
        $this->createBoatTripService->execute(
            false,
            $boats,
            $name,
            $numberHours,
            $startAtHours,
            $startNow,
            $autoStart,
            $isInstructor,
            $isMember,
            $isReservation,
            $note,
            $sailorId
        );
    }
}
