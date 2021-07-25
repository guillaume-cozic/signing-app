<?php


namespace App\Signing\Reporting\Domain\Repositories;


interface BoatTripReportingRepository
{
    public function getNumberBoatTripsForDays(int $days = 60):array;
    public function getNumberBoatTripsByBoatsForDays(int $days = 60):array;
}
