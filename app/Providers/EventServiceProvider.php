<?php

namespace App\Providers;

use App\Listeners\CreateTeam;
use App\Listeners\Teamwork\JoinTeamListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateTeam::class,
            JoinTeamListener::class
        ],
        \Mpociot\Teamwork\Events\UserJoinedTeam::class => [
        ],
        \Mpociot\Teamwork\Events\UserLeftTeam::class => [
        ],
        \Mpociot\Teamwork\Events\UserInvitedToTeam::class => [
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
