<?php


namespace App\Signing\Signing\Domain\UseCases;


interface GetFleetsList
{
    public function execute(?string $search, int $start = 0, int $perPage = 10);
}