<?php

namespace App\Signing\Signing\Domain\Entities\BoatTrip;

use App\Events\BoatTrip\BoatTripStarted;
use App\Signing\Shared\Entities\HasState;
use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatAvailabilityChecker;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;

class Reservation implements HasState
{
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private Id $id,
        private BoatTripDuration $duration,
        private Sailor $sailor,
        private ?BoatsCollection $boats = null,
        private ?string $note = null
    ){
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    /**
     * @throws BoatNotAvailable
     */
    public function create(bool $force = false)
    {
        if(!$force) (new BoatAvailabilityChecker($this->boats, $this->duration->startAt(), $this->duration->hours()))->checkIfEnough();
        $this->boatTripRepository->save($this->getState());
    }

    public function getState(): BoatTripState
    {
        return new BoatTripState(
            $this->id->id(),
            $this->duration->getState(),
            $this->boats->boats(),
            $this->sailor->getState(),
            $this->note
        );
    }


}
