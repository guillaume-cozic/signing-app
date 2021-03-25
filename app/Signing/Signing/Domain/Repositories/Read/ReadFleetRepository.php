<?php


namespace App\Signing\Signing\Domain\Repositories\Read;


interface ReadFleetRepository
{
    public function all(int $page = 1, int $perPage = 10);
    public function getById(string $id);
}
