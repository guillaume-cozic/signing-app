<?php


namespace App\Signing\Signing\Domain\UseCases;


interface DelayBoatTripStart
{
    public function execute(string $id, int $minutes);
}
