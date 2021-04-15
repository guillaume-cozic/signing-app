<?php


namespace App\Signing\Shared\Providers;


use App\Signing\Shared\Entities\User;

interface AuthGateway
{
    public function user():? User;
}
