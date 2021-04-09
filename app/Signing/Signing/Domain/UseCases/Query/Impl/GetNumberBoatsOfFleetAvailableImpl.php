<?php


namespace App\Signing\Signing\Domain\UseCases\Query\Impl;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\Query\GetNumberBoatsOfFleetAvailable;

class GetNumberBoatsOfFleetAvailableImpl implements GetNumberBoatsOfFleetAvailable
{
    public function __construct(
        private ReadFleetRepository $readFleetRepository
    ){}

    public function execute(): array
    {
        return $this->readFleetRepository->getNumberAvailableBoats();
    }
}
