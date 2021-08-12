<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage;


interface DecreaseSailorRentalPackageHoursWhenBoatTripFinished
{
    public function execute(string $boatTripId);
}
