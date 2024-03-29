<?php

namespace App\Signing\Signing\Domain\UseCases\BoatTrip\Impl;

use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\ReservationParameters;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTripDuration;
use App\Signing\Signing\Domain\Entities\BoatTrip\Reservation;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\UseCases\BoatTrip\AddReservation;
use Carbon\Carbon;

class AddReservationImpl implements AddReservation, UseCase
{
    public function execute(
        array $boats,
        string $name,
        float $numberHours,
        string $shouldStartAt,
        bool $isInstructor,
        bool $isMember,
        string $note,
        bool $force = false
    )
    {
        $duration = new BoatTripDuration(
            shouldStartAt: Carbon::createFromFormat('Y-m-d H:i', $shouldStartAt),
            numberHours: $numberHours
        );
        $sailor = new Sailor(name: $name, isInstructor: $isInstructor, isMember: $isMember);
        (new Reservation(new Id(), $duration, $sailor, new BoatsCollection($boats), $note))->create($force);
    }

    public function handle(ReservationParameters|Parameters $params)
    {
         $this->execute(
            $params->boats,
            $params->name,
            $params->numberHours,
            $params->shouldStartAt,
            $params->isInstructor,
            $params->isMember,
            $params->note,
            $params->force,
        );
    }


}
