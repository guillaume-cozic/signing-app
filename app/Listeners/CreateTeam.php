<?php


namespace App\Listeners;


use Illuminate\Auth\Events\Registered;

class CreateTeam
{
    public function handle(Registered $registered)
    {
        $user = $registered->user;

        if(!isset($user->team_name)){
            return;
        }
        $teamModel = config('teamwork.team_model');

        $team = $teamModel::create([
            'name' => $user->team_name,
            'owner_id' => $user->id,
        ]);
        unset($user->team_name);
        $user->attachTeam($team);

        // create fleet
    }
}
