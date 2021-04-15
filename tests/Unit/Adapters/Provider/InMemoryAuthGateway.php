<?php


namespace Tests\Unit\Adapters\Provider;


use App\Signing\Shared\Entities\User;
use App\Signing\Shared\Providers\AuthGateway;

class InMemoryAuthGateway implements AuthGateway
{
    private ?User $user = null;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function user():? User
    {
        return $this->user;
    }
}
