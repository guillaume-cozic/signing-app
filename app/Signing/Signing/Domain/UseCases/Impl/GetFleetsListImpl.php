<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;

class GetFleetsListImpl implements GetFleetsList
{
    public function __construct(private ReadFleetRepository $readFleetRepository){}

    public function execute(?array $search = [], int $start = 0, int $perPage = 10, string $sort = null, string $dirSort = "asc")
    {
        $page = $perPage !== 0 ? $start/$perPage +1 : 0;
        return $this->readFleetRepository->search($search, $page, $perPage, $sort, $dirSort);
    }
}
