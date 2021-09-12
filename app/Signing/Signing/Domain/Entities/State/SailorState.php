<?php


namespace App\Signing\Signing\Domain\Entities\State;


use App\Signing\Shared\Entities\State;
use App\Signing\Signing\Domain\Entities\Sailor;

class SailorState implements State
{
    public function __construct(
        private ?string $name,
        private ?string $memberId = null,
        private ?bool $isInstructor = null,
        private ?bool $isMember = null,
        private ?string $sailorId = null,
    ){}

    public function name(): ?string
    {
        return $this->name !== '' ? $this->name: null;
    }

    public function memberId(): ?string
    {
        return $this->memberId !== '' ? $this->memberId : null;
    }

    public function isInstructor(): ?bool
    {
        return $this->isInstructor;
    }

    public function isMember(): ?bool
    {
        return $this->isMember;
    }

    public function sailorId(): ?string
    {
        return $this->sailorId;
    }

    public function toDomain():Sailor
    {
        return new Sailor(
            $this->memberId,
            $this->name,
            $this->isInstructor,
            $this->isMember,
            $this->sailorId
        );
    }
}
