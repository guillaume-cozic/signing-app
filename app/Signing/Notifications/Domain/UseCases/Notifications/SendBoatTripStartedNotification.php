<?php


namespace App\Signing\Notifications\Domain\UseCases\Notifications;


interface SendBoatTripStartedNotification
{
    public function execute(string $boatTripId, string $userId);
}
