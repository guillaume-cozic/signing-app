<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddBoatTrip
{
    public function execute(array $boats, string $name, int $numberHours, string $startAtHours = null, bool $startNow = null, ?bool $autoStart = false);
}
