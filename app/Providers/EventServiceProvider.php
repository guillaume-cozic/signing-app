<?php

namespace App\Providers;

use App\Listeners\CreateFleets;
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
            JoinTeamListener::class,
            CreateFleets::class
        ],
        \Mpociot\Teamwork\Events\UserJoinedTeam::class => [
        ],
        \Mpociot\Teamwork\Events\UserLeftTeam::class => [
        ],
        \Mpociot\Teamwork\Events\UserInvitedToTeam::class => [
        ],
    ];

    public function boot()
    {
        //
    }
}
