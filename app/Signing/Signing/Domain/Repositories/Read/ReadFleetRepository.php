<?php


namespace App\Signing\Signing\Domain\Repositories\Read;


interface ReadFleetRepository
{
    public function search(?string $search = '', int $page = 1, int $perPage = 10);
    public function getById(string $id);
}
