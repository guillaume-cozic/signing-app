<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class RentalPackageParameters implements Parameters
{
    public function __construct(
        public string $id,
        public array $fleets,
        public string $name,
        public int $days,
    ){}

    public function loggable(): array
    {
        return [];
    }
}
