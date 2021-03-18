<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatAvailabilityChecker;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\AddMemberBoatTrip;

class AddMemberBoatTripImpl implements AddMemberBoatTrip
{
    public function __construct(
        private DateProvider $dateProvider
    ){}

    public function execute(string $memberId, array $boats = [], ?int $numberHours = null)
    {
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        $boatTrip = new BoatTrip(id: new Id(), boatTripDuration: $boatTripDuration, boats: new BoatsCollection($boats), memberId: $memberId);
        $boatTrip->create();
    }

}
