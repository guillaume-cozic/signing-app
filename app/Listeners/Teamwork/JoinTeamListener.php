<?php

namespace App\Listeners\Teamwork;


use Illuminate\Support\Facades\Auth;
use Mpociot\Teamwork\Facades\Teamwork;

class JoinTeamListener
{
    /**
     * See if the session contains an invite token on login and try to accept it.
     * @param mixed $event
     */
    public function handle($event)
    {
        if (session('invite_token')) {
            if ($invite = Teamwork::getInviteFromAcceptToken(session('invite_token'))) {
                Auth::user()->assignRole(json_decode($invite->roles));
                Teamwork::acceptInvite($invite);
            }
            session()->forget('invite_token');
        }
    }
}
