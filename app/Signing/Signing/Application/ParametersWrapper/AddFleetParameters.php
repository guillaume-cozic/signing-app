<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class AddFleetParameters implements Parameters
{
    public function __construct(
        public string $name,
        public int $totalAvailable,
        public string $state,
    ){}

    public function loggable(): array
    {
        return [];
    }
}
