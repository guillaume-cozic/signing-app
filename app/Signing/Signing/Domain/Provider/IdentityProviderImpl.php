<?php


namespace App\Signing\Signing\Domain\Provider;


use Ramsey\Uuid\Uuid;

class IdentityProviderImpl implements IdentityProvider
{
    public function get(): string
    {
        return Uuid::uuid4();
    }

}
