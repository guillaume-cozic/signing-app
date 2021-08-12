<?php


namespace App\Signing\Signing\Application\ViewModel;


class RentalPackageViewModel
{
    public function __construct(
        public string $id,
        public string $name,
        public array $fleetsName,
        public string $validity,
        public int $number,
    ){}
}
