<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class AddBoatTripParameters implements Parameters
{
    public function __construct(
        public array $boats,
        public string $name,
        public float $hours,
        public string $startAt,
        public bool $startNow,
        public bool $startAuto,
        public bool $isInstructor,
        public bool $isMember,
        public string $note,
        public string $sailorId,
        public bool $doNotDecreaseHours
    ){}

    public function loggable(): array
    {
        return [];
    }
}
