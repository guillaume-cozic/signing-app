<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\BoatTrip;

interface BoatTripRepository
{
    public function get(string $id):?BoatTrip;
    public function add(BoatTrip $b);
    public function getBySupport(string $supportId):array;
}
