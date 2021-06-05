<?php


namespace App\Signing\Signing\Domain\UseCases\Fleet;


interface UpdateFleetRentalRate
{
    public function execute(string $id, array $rents);
}
