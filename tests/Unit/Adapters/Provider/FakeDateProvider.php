<?php


namespace Tests\Unit\Adapters\Provider;


use App\Signing\Shared\Providers\DateProvider;

class FakeDateProvider implements DateProvider
{
    public function __construct(private \DateTime $current)
    {
        $this->current = new \DateTime();
    }

    public function current(): \DateTime
    {
        return clone $this->current;
    }

}
