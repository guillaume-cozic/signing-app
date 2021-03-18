<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddMemberBoatTrip
{
    public function execute(string $memberId, array $boats = [], ?int $numberHours = null);
}
