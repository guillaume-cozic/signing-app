<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddBoatTrip
{
    public function execute(string $supportId, int $quantity, string $name, int $numberHours);
}
