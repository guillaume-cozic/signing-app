<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\BoatTripIdentityParameters;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;

class EndBoatTripImpl implements EndBoatTrip, UseCase
{
    public function __construct(
        private BoatTripRepository $boatTripRepository,
        private DateProvider $dateProvider,
    ){}

    public function execute(string $boatTripId)
    {
        $boatTrip = $this->boatTripRepository->get($boatTripId);
        $boatTrip->end($this->dateProvider->current());
    }

    public function handle(BoatTripIdentityParameters|Parameters $parameters)
    {
        $this->execute($parameters->id);
    }
}
