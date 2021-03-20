<?php


namespace App\Signing\Signing\Domain\Entities;


class BoatTripState implements State
{
    public function __construct(
        private string $id,
        private array $duration,
        private array $boats,
        private ?string $name,
        private ?string $memberId
    ){}

    public function id(): string
    {
        return $this->id;
    }

    public function duration(): array
    {
        return $this->duration;
    }

    public function name(): ?string
    {
        return $this->name;
    }
    public function memberId(): ?string
    {
        return $this->memberId;
    }
}
