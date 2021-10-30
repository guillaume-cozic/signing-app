<?php

namespace App\Signing\Signing\Application\ParametersWrapper;

use App\Signing\Shared\Services\UseCaseHandler\Parameters;

class BoatTripIdentityParameters implements Parameters
{
    public function __construct(
        public string $id
    ){}

    public function loggable(): array
    {
        return [];
    }
}
