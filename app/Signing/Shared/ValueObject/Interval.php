<?php

namespace App\Signing\Shared\ValueObject;

use App\Signing\Shared\Exception\IntervalException;

class Interval
{
    public function __construct(
        private ?float $start,
        private ?float $end
    )
    {
        if($this->start < 0 || $this->end < 0){
            throw new IntervalException();
        }
        if(isset($this->start) && isset($this->end) && $this->start > $this->end){
            throw new IntervalException();
        }
    }

    public function start(): ?float
    {
        return $this->start;
    }

    public function end(): ?float
    {
        return $this->end;
    }
}
