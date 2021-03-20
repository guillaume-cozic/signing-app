<?php


namespace App\Signing\Signing\Domain\Entities\State;


use App\Signing\Signing\Domain\Entities\State;

class SailorState implements State
{
    public function __construct(
        private ?string $name,
        private ?string $memberId
    ){}

    public function name(): ?string
    {
        return $this->name !== '' ? $this->name: null;
    }

    public function memberId(): ?string
    {
        return $this->memberId !== '' ? $this->memberId : null;
    }
}
