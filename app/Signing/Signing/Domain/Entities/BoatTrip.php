<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use JetBrains\PhpStorm\Pure;
use \App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;

class BoatTrip implements HasState
{
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private Id $id,
        private BoatTripDuration $boatTripDuration,
        private ?BoatsCollection $boats = null,
        private ?string $name = null,
        private ?string $memberId = null,
    ){
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    #[Pure] public function id():string
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
        (new BoatAvailabilityChecker($this->boats))->checkIfEnoughBoat();
        $this->boatTripRepository->add($this);
    }

    public function end(\DateTime $endDate)
    {
        $this->boatTripDuration->end($endDate);
        $this->boatTripRepository->add($this);
    }

    public function addTime(float $numberHours)
    {
        $this->boatTripDuration->addTime($numberHours);
        $this->boatTripRepository->add($this);
    }

    public function quantity(string $supportId):int
    {
        return $this->boats->quantity($supportId) ?? 0;
    }

    public function getState(): BoatTripState
    {
        $boatTripDuration = $this->boatTripDuration->toArray();
        return new BoatTripState($this->id->id(), $boatTripDuration, $this->boats->boats(), $this->name, $this->memberId);
    }
}
