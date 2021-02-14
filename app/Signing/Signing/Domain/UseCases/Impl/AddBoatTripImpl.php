<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTripChecker;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;

class AddBoatTripImpl implements AddBoatTrip
{
    public function __construct(
        private DateProvider $dateProvider
    ){}

    public function execute(string $supportId, int $quantity, string $name, int $numberHours)
    {
        (new BoatTripChecker(supportId: $supportId, qtyAsked: $quantity))->checkIfEnoughBoat();

        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        $boatTrip = new BoatTrip(new Id(), $boatTripDuration, $supportId, $quantity, $name);
        $boatTrip->create();
    }
}
