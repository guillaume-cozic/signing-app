<?php


namespace App\Listeners\BoatTrip;


use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripStartedNotification;
use App\Signing\Signing\Domain\Events\BoatTrip\BoatTripStarted;

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
