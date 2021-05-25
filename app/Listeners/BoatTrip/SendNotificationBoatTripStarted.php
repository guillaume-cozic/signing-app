<?php


namespace App\Listeners\BoatTrip;


use App\Events\BoatTrip\BoatTripEnded;
use App\Events\BoatTrip\BoatTripStarted;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripStartedNotification;

class SendNotificationBoatTripStarted
{
    public function __construct(
        private SendBoatTripStartedNotification $sendBoatTripStartedNotification
    ){}

    public function handle(BoatTripStarted $boatTripEnded)
    {
        $this->sendBoatTripStartedNotification->execute($boatTripEnded->boatTripId, $boatTripEnded->userId);
    }
}
