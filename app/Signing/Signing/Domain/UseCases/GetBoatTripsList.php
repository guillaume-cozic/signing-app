<?php


namespace App\Signing\Signing\Domain\UseCases;


interface GetBoatTripsList
{
    public function execute(int $start = 0, int $perPage = 10);
}
