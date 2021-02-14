<?php


namespace App\Signing\Signing\Domain\UseCases;


interface AddSupport
{
    public function execute(string $title, string $description, int $totalAvailable);
}
