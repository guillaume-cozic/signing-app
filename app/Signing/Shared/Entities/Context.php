<?php


namespace App\Signing\Shared\Entities;


class Context
{
    public function __construct(private int $sailingClubId){}

    public function sailingClubId():int
    {
        return $this->sailingClubId;
    }
}
