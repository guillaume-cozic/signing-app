<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddBoatTrip
{
    public function execute(
        array $boats,
        string $name,
        float $numberHours,
        string $startAtHours = null,
        bool $startNow = null,
        ?bool $autoStart = false,
        bool $isInstructor = false,
        bool $isMember = false,
        ?string $note = null,
        ?string $sailorId = null,
        bool $doNotDecreaseHours = false,
    );
}
