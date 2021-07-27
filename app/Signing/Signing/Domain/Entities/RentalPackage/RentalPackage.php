<?php


namespace App\Signing\Signing\Domain\Entities\RentalPackage;


class RentalPackage
{
    public function __construct(
        private string $id,
        private array $fleets
    ){}

    public function id():string
    {
        return $this->id;
    }
}
