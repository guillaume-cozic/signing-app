<?php

namespace App\Signing\Signing\Domain\UseCases\BoatTrip;

interface AddReservation
{
    public function execute(
        array $boats,
        string $name,
        float $numberHours,
        string $shouldStartAt,
        bool $isInstructor,
        bool $isMember,
        string $note,
        bool $force = false
    );
}
