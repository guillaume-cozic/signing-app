<?php


namespace App\Signing\Signing\Domain\UseCases;


interface GetFleetsList
{
    public function execute(?array $search = [], int $start = 0, int $perPage = 10, string $sort = null, string $dirSort = "asc");
}
