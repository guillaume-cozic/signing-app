<?php


namespace App\Signing\Signing\Domain\Repositories\Read;


interface ReadBoatTripRepository
{
    public function getInProgress(int $page = 1, int $perPage = 10);
}
