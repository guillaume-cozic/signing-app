<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;

use App\Signing\Shared\Exception\DomainException;
use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\AddBoatTripParameters;
use App\Signing\Signing\Domain\DomainServices\CreateBoatTripService;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;

class ForceAddBoatTripImpl implements ForceAddBoatTrip, UseCase
{
    public function __construct(
        private CreateBoatTripService $createBoatTripService
    ){}

    public function execute(
        array $boats,
        string $name,
        float $numberHours,
        string $startAtHours = null,
        bool $startNow = null,
        ?bool $autoStart = false,
        bool $isInstructor = false,
        bool $isMember = false,
        ?string $note = null,
        ?string $sailorId = null,
        bool $doNotDecreaseHours = false,
    )
    {
        $this->createBoatTripService->execute(
            true,
            $boats,
            $name,
            $numberHours,
            $startAtHours,
            $startNow,
            $autoStart,
            $isInstructor,
            $isMember,
            $note,
            $sailorId,
            $doNotDecreaseHours
        );
    }

    public function handle(AddBoatTripParameters|Parameters $parameters)
    {
        $this->execute(
            $parameters->boats,
            $parameters->name,
            $parameters->hours,
            $parameters->startAt,
            $parameters->startNow,
            $parameters->startAuto,
            $parameters->isInstructor,
            $parameters->isMember,
            $parameters->note,
            $parameters->sailorId,
            $parameters->doNotDecreaseHours
        );
    }
}
