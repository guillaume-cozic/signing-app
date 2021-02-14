<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddMemberBoatTrip
{
    public function execute(string $memberId, ?string $supportId = null, ?int $numberBoats = null, ?int $numberHours = null);
}
