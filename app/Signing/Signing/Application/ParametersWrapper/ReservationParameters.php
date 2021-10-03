<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class ReservationParameters implements Parameters
{
    public function __construct(
        public array $boats,
        public string $name,
        public float $numberHours,
        public string $shouldStartAt,
        public bool $isInstructor,
        public bool $isMember,
        public string $note,
        public bool $force = false){}

    public function loggable(): array
    {
        return [];
    }
}
