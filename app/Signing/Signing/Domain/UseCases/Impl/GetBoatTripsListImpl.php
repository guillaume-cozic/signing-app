<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;

class GetBoatTripsListImpl implements GetBoatTripsList
{
    public function __construct(
        private ReadBoatTripRepository $boatTripRepository
    ){}

    public function execute(?string $search = '', int $start = 0, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = [])
    {
        $page = $start/$perPage +1;
        return $this->boatTripRepository->getInProgress($search, $page, $perPage, $sort, $dirSort, $filters);
    }
}
