<?php


namespace App\Signing\Notifications\Domain\UseCases\Notifications;


interface SendBoatTripEndedNotification
{
    public function execute(string $boatTripId, string $userId);
}
