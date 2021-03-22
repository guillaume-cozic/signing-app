<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;

class GetFleetsListImpl implements GetFleetsList
{
    public function __construct(private ReadFleetRepository $readFleetRepository){}

    public function execute()
    {
        return $this->readFleetRepository->all();
    }
}
