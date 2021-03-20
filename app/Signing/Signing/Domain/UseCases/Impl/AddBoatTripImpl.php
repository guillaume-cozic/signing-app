<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatAvailabilityChecker;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Entities\BoatTripDuration;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;

class AddBoatTripImpl implements AddBoatTrip
{
    public function __construct(
        private DateProvider $dateProvider
    ){}

    /**
     * @throws BoatNotAvailable
     */
    public function execute(array $boats, string $name, int $numberHours)
    {
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        $boatTrip = new BoatTrip(new Id(), $boatTripDuration, new Sailor(name:$name), new BoatsCollection($boats));
        $boatTrip->create();
    }
}
