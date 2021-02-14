<?php


namespace App\Signing\Shared\Entities;


use App\Signing\Signing\Domain\Provider\IdentityProvider;

class Id
{
    private IdentityProvider $identityProvider;

    public function __construct(private ?string $uuid = null)
    {
        $this->identityProvider = app(IdentityProvider::class);
        $this->uuid = $this->uuid ?? $this->identityProvider->get();
    }

    public function id():string
    {
        return $this->uuid;
    }
}
