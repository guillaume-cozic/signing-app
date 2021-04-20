<?php


namespace App\Signing\Signing\Domain\UseCases\BoatTrip;


interface ForceAddBoatTrip
{
    public function execute(array $boats, string $name, int $numberHours, string $startAtHours = null, bool $startNow = null);
}
