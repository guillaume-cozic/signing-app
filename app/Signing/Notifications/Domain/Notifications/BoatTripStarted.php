<?php

namespace App\Signing\Notifications\Domain\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BoatTripStarted extends Notification
{
    use Queueable;

    public function __construct(
        private string $boatTripId,
        private string $userId
    ){}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return [
            'boat_trip_id' => $this->boatTripId,
            'user_id' => $this->userId,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'boat_trip_id' => $this->boatTripId,
            'user_id' => $this->userId,
        ];
    }

    public function format()
    {

    }
}
