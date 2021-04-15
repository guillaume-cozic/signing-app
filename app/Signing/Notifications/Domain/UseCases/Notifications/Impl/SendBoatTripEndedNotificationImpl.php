<?php


namespace App\Signing\Notifications\Domain\UseCases\Notifications\Impl;


use App\Events\NotificationCreated;
use App\Models\User;
use App\Signing\Notifications\Domain\Notifications\BoatTripEnded;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripEndedNotification;
use Illuminate\Support\Facades\Notification;

class SendBoatTripEndedNotificationImpl implements SendBoatTripEndedNotification
{
    public function execute(string $boatTripId, string $userId)
    {
        $boatTripEndedNotification = new BoatTripEnded($boatTripId, $userId);
        $users = User::where('uuid', $userId)->first()->currentTeam()->first()->users()->get();
        Notification::send($users, $boatTripEndedNotification);
        event(new NotificationCreated());
    }

}
