<?php


namespace App\Signing\Shared\Providers\Impl;


use App\Signing\Shared\Providers\DateProvider;

class DateProviderImpl implements DateProvider
{
    public function current(): \DateTime
    {
        return new \DateTime();
    }
}
