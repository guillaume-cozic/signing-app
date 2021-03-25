<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;

class GetFleetsListImpl implements GetFleetsList
{
    public function __construct(private ReadFleetRepository $readFleetRepository){}

    public function execute(?string $search = '', int $start = 0, int $perPage = 10, string $sort = null, string $dirSort = "asc")
    {
        $page = $start/$perPage +1;
        return $this->readFleetRepository->search($search, $page, $perPage, $sort, $dirSort);
    }
}
