<?php


namespace App\Signing\Signing\Domain\UseCases;


interface GetBoatTripsList
{
    public function execute(?string $search = '', int $start = 0, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = []);
}
