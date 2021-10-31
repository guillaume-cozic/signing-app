<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class SailorRentalPackageParameters implements Parameters
{
    public function __construct(
        public string $id,
        public string $rentalPackageId,
        public string $name,
        public float $hours,
        public ?string $sailorId,
    ){}

    public function loggable(): array
    {
        return [];
    }
}
