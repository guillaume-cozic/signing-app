<?php


namespace App\Signing\Signing\Application\ViewModel;


use Carbon\Carbon;

class ActionsSailorRentalPackageViewModel
{
    public function __construct(
        public string $type,
        public float $hours,
        public Carbon $atTime
    ){}
}
