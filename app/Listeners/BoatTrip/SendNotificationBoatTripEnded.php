<?php


namespace App\Listeners\BoatTrip;


use App\Events\BoatTrip\BoatTripEnded;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripEndedNotification;

class SendNotificationBoatTripEnded
{
    public function __construct(
        private SendBoatTripEndedNotification $sendBoatTripEndedNotification
    ){}

    public function handle(BoatTripEnded $boatTripEnded)
    {
        $this->sendBoatTripEndedNotification->execute($boatTripEnded->boatTripId, $boatTripEnded->userId);
    }
}
