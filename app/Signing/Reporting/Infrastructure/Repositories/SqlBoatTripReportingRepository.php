<?php


namespace App\Signing\Reporting\Infrastructure\Repositories;


use App\Signing\Reporting\Domain\Repositories\BoatTripReportingRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use Illuminate\Database\Eloquent\Builder;

class SqlBoatTripReportingRepository implements BoatTripReportingRepository
{
    public function getNumberBoatTripsForDays(int $days = 30)
    {
        $reporting = [];

        $formatKey = 'd M';
        for($i=$days; $i >= 0; $i--){
            $reporting[date('Y')][(new \DateTime('-'.$i.' days'))->format($formatKey)] = 0;
            $j = $i + 365;
            $reporting[date('Y') - 1][(new \DateTime('-'.$j.' days'))->format($formatKey)] = 0;
        }

        $boatTrips = BoatTripModel::query()
            ->sailingClub()
            ->where(function (Builder $query) use ($days) {
                $query->where('start_at', '>=', new \DateTime('-' . $days . ' days'));
            })
            ->orWhere(function (Builder $query) use($days) {
                $daysStartLastYears = $days + 365;
                $daysEndLastYears = 365;
                $query->where('start_at', '>=', new \DateTime('-' . $daysStartLastYears . ' days'))
                      ->where('start_at', '<=', new \DateTime('-' . $daysEndLastYears . ' days'));
            })
            ->get()
        ;

        foreach ($boatTrips as $key => $boatTrip){
            $reporting[$boatTrip->start_at->format('Y')][$boatTrip->start_at->format($formatKey)] += $boatTrip->totalBoats();
        }
        return $reporting;
    }
}