<?php

declare(strict_types = 1);

namespace App\Signing\Reporting\Domain\Charts;

use App\Signing\Reporting\Domain\Repositories\BoatTripReportingRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class BoatTripsByFleet extends BaseChart
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
        $reporting = $this->boatTripReportingRepository->getNumberBoatTripsByBoatsForDays();

        $chartisan = Chartisan::build();

        $values = $labels = [];
        foreach($reporting as $boat => $number){
            $fleet = FleetModel::where('uuid', $boat)->first();
            $labels[] = $fleet->name;
            $values[] = $number;
        }

        $chartisan->dataset('Flottes',  $values);

        $chartisan->labels($labels);
        return $chartisan;
    }
}
