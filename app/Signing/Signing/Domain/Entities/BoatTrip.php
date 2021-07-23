<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Events\BoatTrip\BoatTripEnded;
use App\Events\BoatTrip\BoatTripStarted;
use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\AuthGateway;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use \App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use \App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use \App\Signing\Signing\Domain\Exceptions\TimeCantBeNegative;

class BoatTrip implements HasState
{
    private BoatTripRepository $boatTripRepository;
    private AuthGateway $authGateway;

    public function __construct(
        private Id $id,
        private BoatTripDuration $duration,
        private Sailor $sailor,
        private ?BoatsCollection $boats = null,
    ){
        $this->boatTripRepository = app(BoatTripRepository::class);
        $this->authGateway = app(AuthGateway::class);
    }

    public function id():string
    {
        return $this->id->id();
    }

    /**
     * @throws BoatNotAvailable
     */
    public function create(bool $force = false, bool $startNow = null)
    {
        if(!$force) (new BoatAvailabilityChecker($this->boats, $this->duration->startAt(), $this->duration->hours()))->checkIfEnough();
        $this->boatTripRepository->save($this->getState());
        if($startNow) {
            $currentUser = $this->authGateway->user();
            event(new BoatTripStarted($this->id(), $currentUser->id()));
        }
    }

    /**
     * @throws BoatTripAlreadyEnded
     */
    public function end(\DateTime $endDate)
    {
        $this->duration->end($endDate);
        $this->boatTripRepository->save($this->getState());
        $currentUser = $this->authGateway->user();
        event(new BoatTripEnded($this->id->id(), $currentUser->id()));
    }

    /**
     * @throws BoatTripAlreadyEnded
     * @throws TimeCantBeNegative
     */
    public function addTime(float $numberHours)
    {
        $this->duration->addTime($numberHours);
        $this->boatTripRepository->save($this->getState());
    }


    public function start()
    {
        if($this->duration->isStarted()) return;
        $this->duration->start();
        $this->boatTripRepository->save($this->getState());
        $currentUser = $this->authGateway->user();
        event(new BoatTripStarted($this->id->id(), $currentUser->id()));
    }

    /**
     * @throws BoatTripAlreadyEnded
     * @throws TimeCantBeNegative
     */
    public function delayStart(int $minutes)
    {
        $this->duration->delayStart($minutes);
        $this->boatTripRepository->save($this->getState());
    }

    public function cancel()
    {
        if($this->duration->isEnded()) throw new BoatTripAlreadyEnded();
        $this->boatTripRepository->delete($this->id());
    }

    public function quantity(string $boatId):int
    {
        return $this->boats->quantity($boatId) ?? 0;
    }

    public function getState(): BoatTripState
    {
        return new BoatTripState(
            $this->id->id(),
            $this->duration->getState(),
            $this->boats->boats(),
            $this->sailor->getState()
        );
    }
}
