<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddFleet
{
    public function execute(string $title, string $description, int $totalAvailable);
}
