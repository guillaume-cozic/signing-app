<?php

declare(strict_types = 1);

namespace App\Signing\Reporting\Domain\Charts;

use App\Signing\Reporting\Domain\Repositories\BoatTripReportingRepository;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class BoatTripsByDay extends BaseChart
{
    public function __construct(
        private BoatTripReportingRepository $boatTripReportingRepository
    ){}

    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $reporting = $this->boatTripReportingRepository->getNumberBoatTripsForDays();

        return Chartisan::build()
            ->labels(array_keys($reporting[date('Y')]))
            ->dataset((string)(date('Y') - 1), array_values($reporting[date('Y') - 1]))
            ->dataset((string)(date('Y')), array_values($reporting[date('Y')]));
    }
}
