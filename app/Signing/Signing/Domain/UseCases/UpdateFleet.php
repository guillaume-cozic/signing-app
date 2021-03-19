<?php


namespace App\Signing\Signing\Domain\UseCases;


interface UpdateFleet
{
    public function execute(string $id, int $newTotal);
}
