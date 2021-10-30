<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;


use App\Signing\Shared\Exception\DomainException;
use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\BoatTripIdentityParameters;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\BoatTrip\StartBoatTrip;

class StartBoatTripImpl implements StartBoatTrip, UseCase
{
    public function __construct(
        private BoatTripRepository $boatTripRepository
    ){}

    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        if(!isset($boatTrip)){
            return;
        }
        $boatTrip->start();
    }

    public function handle(BoatTripIdentityParameters|Parameters $parameters)
    {
        $this->execute($parameters->id);
    }
}
