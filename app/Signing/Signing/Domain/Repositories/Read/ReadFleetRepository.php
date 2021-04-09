<?php


namespace App\Signing\Signing\Domain\Repositories\Read;


interface ReadFleetRepository
{
    public function search(?array $search = [], int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc");
    public function getById(string $id);
    public function getNumberAvailableBoats();
}
