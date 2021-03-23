<?php


namespace App\Signing\Signing\Domain\UseCases;


interface DisableFleet
{
    public function execute(string $fleetId);
}
