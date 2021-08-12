<?php

namespace App\Providers;

use App\Events\BoatTrip\BoatTripEnded;
use App\Events\BoatTrip\BoatTripStarted;
use App\Listeners\BoatTrip\DecreaseSailorRentalPackageHoursListener;
use App\Listeners\BoatTrip\SendNotificationBoatTripEnded;
use App\Listeners\BoatTrip\SendNotificationBoatTripStarted;
use App\Listeners\CreateTeam;
use App\Listeners\Teamwork\JoinTeamListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateTeam::class,
            JoinTeamListener::class
        ],
        BoatTripEnded::class => [
            SendNotificationBoatTripEnded::class,
            DecreaseSailorRentalPackageHoursListener::class
        ],
        BoatTripStarted::class => [
            SendNotificationBoatTripStarted::class
        ],
        \Mpociot\Teamwork\Events\UserJoinedTeam::class => [],
        \Mpociot\Teamwork\Events\UserLeftTeam::class => [],
        \Mpociot\Teamwork\Events\UserInvitedToTeam::class => [],
    ];

    public function boot()
    {
        //
    }
}
