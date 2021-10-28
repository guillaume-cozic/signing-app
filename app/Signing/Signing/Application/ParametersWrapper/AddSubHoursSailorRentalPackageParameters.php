<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class AddSubHoursSailorRentalPackageParameters implements Parameters
{
    public function __construct(
        public string $id,
        public float $hours,
    ){}

    public function loggable(): array
    {
        return [];
    }
}
