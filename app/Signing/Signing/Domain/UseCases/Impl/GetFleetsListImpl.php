<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;

class GetFleetsListImpl implements GetFleetsList
{
    public function __construct(private ReadFleetRepository $readFleetRepository){}

    public function execute(int $start = 0, int $perPage = 10)
    {
        $page = $start/$perPage +1;
        return $this->readFleetRepository->all($page, $perPage);
    }
}
