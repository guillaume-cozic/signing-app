<?php


namespace App\Signing\Reporting\Domain\Repositories;


interface BoatTripReportingRepository
{
    public function getNumberBoatTripsForDays(int $days = 30);
}
