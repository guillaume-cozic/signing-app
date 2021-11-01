<?php


namespace App\Listeners\BoatTrip;


use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripEndedNotification;
use App\Signing\Signing\Domain\Events\BoatTrip\BoatTripEnded;

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
