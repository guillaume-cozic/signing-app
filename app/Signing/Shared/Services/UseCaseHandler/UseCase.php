<?php

namespace App\Signing\Shared\Services\UseCaseHandler;

use App\Signing\Shared\Exception\DomainException;

interface UseCase
{
    /**
     * @param Parameters $parameters
     * @throws DomainException
     * @return mixed
     */
    public function handle(Parameters $parameters);
}
