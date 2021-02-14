<?php


namespace Tests\Unit\Adapters\Provider;


use App\Signing\Signing\Domain\Provider\IdentityProvider;
use Ramsey\Uuid\Uuid;

class FakeIdentityProvider implements IdentityProvider
{
    public function __construct(
        private array $ids = []
    ){}

    public function get(): string
    {
        return !empty($this->ids) ? array_pop($this->ids) : Uuid::uuid4();
    }

    public function add(string $uuid)
    {
        $this->ids[] = $uuid;
    }
}
