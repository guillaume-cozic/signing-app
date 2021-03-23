<?php


namespace App\Signing\Signing\Domain\UseCases;


interface EnableFleet
{
    public function execute(string $fleetId);
}
