<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use \App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use \App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use \App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;

class BoatTrip implements HasState
{
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private Id $id,
        private BoatTripDuration $duration,
        private Sailor $sailor,
        private ?BoatsCollection $boats = null,
    ){
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    public function id():string
    {
        return $this->id->id();
    }

    public function hasBoat(string $boatIdAsked):bool
    {
        foreach ($this->boats->boats() as $boatId => $qty) {
            if($boatIdAsked === $boatId){
                return true;
            }
        }
        return false;
    }

    /**
     * @throws BoatNotAvailable
     */
    public function create()
    {
        (new BoatAvailabilityChecker($this->boats))->checkIfEnough();
        $this->boatTripRepository->add($this);
    }

    /**
     * @throws BoatTripAlreadyEnded
     */
    public function end(\DateTime $endDate)
    {
        $this->duration->end($endDate);
        $this->boatTripRepository->add($this);
    }

    /**
     * @throws BoatTripAlreadyEnded
     * @throws TimeCantBeNegative
     */
    public function addTime(float $numberHours)
    {
        $this->duration->addTime($numberHours);
        $this->boatTripRepository->add($this);
    }

    public function quantity(string $boatId):int
    {
        return $this->boats->quantity($boatId) ?? 0;
    }

    public function getState(): BoatTripState
    {
        $boatTripDuration = $this->duration->toArray();
        return new BoatTripState($this->id->id(), $boatTripDuration, $this->boats->boats(), $this->sailor);
    }
}
