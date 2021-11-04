<?php


namespace App\Signing\Notifications\Domain\UseCases\Notifications\Impl;


use App\Models\User;
use App\Signing\Notifications\Domain\Events\NotificationCreated;
use App\Signing\Notifications\Domain\Notifications\BoatTripStarted;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripStartedNotification;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use Illuminate\Support\Facades\Notification;

class SendBoatTripStartedNotificationImpl implements SendBoatTripStartedNotification
{
    public function execute(string $boatTripId, string $userId)
    {
        $boatTripEndedNotification = new BoatTripStarted($boatTripId, $userId);
        $performer = User::where('uuid', $userId)->first();
        $users = $performer->currentTeam()->first()->users()->get();

        $usersExceptMe = [];
        foreach ($users as $user){
            if($user->id !== $performer->id) {
                $usersExceptMe[] = $user;
            }
        }
        Notification::send($users, $boatTripEndedNotification);

        $boatTrip = BoatTripModel::where('uuid', $boatTripId)->first();
        $message = __('notification.user_start_boat_trip', [
            'user' => ucfirst($performer->firstname),
            'sailor' => $boatTrip->name,
        ]);
        event(new NotificationCreated($message, 'Sortie démarée', $performer->adminlte_image(), 'info', $performer->id));
    }
}
