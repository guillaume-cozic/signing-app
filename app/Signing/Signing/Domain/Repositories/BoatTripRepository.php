<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTripState;

interface BoatTripRepository
{
    public function get(string $id):?BoatTrip;
    public function save(BoatTripState $b);
    public function getInProgressByBoat(string $boatId):array;
    public function getUsedBoat(string $boatId, \DateTime $startAt, \DateTime $provisionalEndDate):array;
    public function delete(string $boatTripId);
}
