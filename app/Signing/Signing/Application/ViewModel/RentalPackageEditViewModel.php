<?php


namespace App\Signing\Signing\Application\ViewModel;


class RentalPackageEditViewModel
{
    public function __construct(
        public string $id,
        public string $name,
        public array $fleets,
        public string $validity
    ){}
}
