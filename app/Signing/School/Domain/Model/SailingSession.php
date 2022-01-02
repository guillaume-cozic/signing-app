<?php

namespace App\Signing\School\Domain\Model;

use App\Signing\School\Domain\Model\Exception\DateInThePast;
use App\Signing\School\Domain\Model\Exception\OverlapDate;

class SailingSession
{
    public function __construct(
        private int $start,
        private int $end
    ){}

    public function check()
    {
        $now = time();
        if($this->start < $now || $this->end < $now){
            throw new DateInThePast('can_not_plan_internship_session_in_the_past');
        }
    }

    public function checkOverlapWithSession(SailingSession $session)
    {
        if (($this->start >= $session->start() && $this->start <= $session->end())
            || ($this->end >= $session->start() && $this->end <= $session->end())) {
            throw new OverlapDate();
        }
    }

    public function start(): int
    {
        return $this->start;
    }

    public function end(): int
    {
        return $this->end;
    }
}
