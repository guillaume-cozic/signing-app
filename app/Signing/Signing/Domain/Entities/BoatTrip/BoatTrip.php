<?php
declare(strict_types=1);

namespace App\Signing\Signing\Domain\Entities\BoatTrip;

use App\Signing\Shared\Entities\HasState;
use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\AuthGateway;
use App\Signing\Signing\Domain\Entities\BoatAvailabilityChecker;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Events\BoatTrip\BoatTripEnded;
use App\Signing\Signing\Domain\Events\BoatTrip\BoatTripStarted;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use \App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use \App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use Illuminate\Support\Facades\Log;

class BoatTrip implements HasState
{
    private BoatTripRepository $boatTripRepository;
    private AuthGateway $authGateway;

    public function __construct(
        private Id $id,
        private BoatTripDuration $duration,
        private Sailor $sailor,
        private ?BoatsCollection $boats = null,
        private ?string $note = null,
        private array $options = [],
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
        $this->duration = $this->duration->end($endDate);
        $this->boatTripRepository->save($this->getState());
        $currentUser = $this->authGateway->user();
        event(new BoatTripEnded($this->id->id(), $currentUser->id()));
    }

    public function start()
    {
        if($this->duration->isStarted()) return;
        $this->duration = $this->duration->start();
        $this->boatTripRepository->save($this->getState());
        $currentUser = $this->authGateway->user();
        event(new BoatTripStarted($this->id->id(), $currentUser->id()));
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

    private function hoursUsed():array
    {
        $hours = [];
        foreach($this->boats->boats() as $boat => $qty){
            $hours[$boat] = $this->duration->hours() * $qty;
        }
        return $hours;
    }

    public function updateSailorRentalPackage()
    {
        if($this->options['do_not_decrease_hours'] === true) return;
        foreach($this->hoursUsed() as $boatId => $hours){
            $rentalPackage = app(RentalPackageRepository::class)->getByFleet($boatId);
            if(isset($rentalPackage)){
                $this->sailor->decreaseHoursRentalPackage($rentalPackage, $hours);
            }
        }
    }

    public function getState(): BoatTripState
    {
        return new BoatTripState(
            $this->id->id(),
            $this->duration->getState(),
            $this->boats->boats(),
            $this->sailor->getState(),
            $this->note,
            $this->options,
        );
    }
}
