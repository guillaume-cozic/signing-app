<?php


namespace App\Signing\Signing\Application\ViewModel;


class SailorRentalPackageViewModel
{
    public function __construct(
        public string $id,
        public string $sailorName,
        public string $rentalName,
        public string $hours
    ){}
}
