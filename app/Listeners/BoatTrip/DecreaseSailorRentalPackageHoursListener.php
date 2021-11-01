<?php


namespace App\Listeners\BoatTrip;


use App\Signing\Signing\Domain\Events\BoatTrip\BoatTripEnded;
use App\Signing\Signing\Domain\UseCases\RentalPackage\DecreaseSailorRentalPackageHoursWhenBoatTripFinished;

class DecreaseSailorRentalPackageHoursListener
{
    public function __construct(
        private DecreaseSailorRentalPackageHoursWhenBoatTripFinished $decreaseSailorRentalPackageHoursWhenBoatTripFinished
    ){}


    public function handle(BoatTripEnded $boatTripEnded)
    {
        $this->decreaseSailorRentalPackageHoursWhenBoatTripFinished->execute($boatTripEnded->boatTripId);
    }
}
