<?php


namespace App\Signing\Shared\Providers\Impl;


use App\Signing\Shared\Entities\User;
use App\Signing\Shared\Providers\AuthGateway;
use Illuminate\Support\Facades\Auth;

class AuthGatewayImpl implements AuthGateway
{
    public function user(): ?User
    {
        return Auth::check() ? new User(Auth::user()->uuid) : null;
    }
}

