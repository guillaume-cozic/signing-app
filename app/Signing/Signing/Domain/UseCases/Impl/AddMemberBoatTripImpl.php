<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTripChecker;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\AddMemberBoatTrip;

class AddMemberBoatTripImpl implements AddMemberBoatTrip
{
    public function __construct(
        private DateProvider $dateProvider
    ){}

    public function execute(string $memberId, ?string $supportId = null, ?int $numberBoats = null, ?int $numberHours = null)
    {
        if(isset($supportId)) {
            (new BoatTripChecker(supportId: $supportId, qtyAsked: $numberBoats))->checkIfEnoughBoat();
        }

        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        $boatTrip = new BoatTrip(id:new Id(), boatTripDuration: $boatTripDuration, supportId: $supportId, qty: $numberBoats, memberId: $memberId);
        $boatTrip->create();
    }

}
