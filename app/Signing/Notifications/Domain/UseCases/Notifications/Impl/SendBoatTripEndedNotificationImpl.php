<?php


namespace App\Signing\Notifications\Domain\UseCases\Notifications\Impl;


use App\Models\User;
use App\Signing\Notifications\Domain\Events\NotificationCreated;
use App\Signing\Notifications\Domain\Notifications\BoatTripEnded;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripEndedNotification;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use Illuminate\Support\Facades\Notification;

class SendBoatTripEndedNotificationImpl implements SendBoatTripEndedNotification
{
    public function execute(string $boatTripId, string $userId)
    {
        $boatTripEndedNotification = new BoatTripEnded($boatTripId, $userId);
        $performer = User::where('uuid', $userId)->first();
        $users = $performer->currentTeam()->first()->users()->get();
        Notification::send($users, $boatTripEndedNotification);

        $boatTrip = BoatTripModel::where('uuid', $boatTripId)->first();
        $message = __('notification.user_end_boat_trip', [
            'user' => ucfirst($performer->firstname),
            'sailor' => $boatTrip->name,
        ]);
        event(new NotificationCreated($message, 'Sortie terminée', $performer->adminlte_image(), 'info'));
    }
}
