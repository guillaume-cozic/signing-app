<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTripState;

interface BoatTripRepository
{
    public function get(string $id):?BoatTrip;
    public function save(BoatTripState $b);
    public function getInProgressByBoat(string $boatId):array;
}
