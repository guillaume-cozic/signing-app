<?php


namespace App\Listeners;


use App\Signing\Signing\Domain\UseCases\System\CreateFleetWhenTeamCreated;
use Illuminate\Auth\Events\Registered;

class CreateFleets
{
    public function handle(Registered $registered)
    {
        app(CreateFleetWhenTeamCreated::class)->execute();
    }
}
