<?php


namespace App\Signing\Reporting\Infrastructure\Repositories;


use App\Signing\Reporting\Domain\Repositories\BoatTripReportingRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use Illuminate\Database\Eloquent\Builder;

class SqlBoatTripReportingRepository implements BoatTripReportingRepository
{
    public function getNumberBoatTripsByBoatsForDays(int $days = 90):array
    {
        $boatTrips = BoatTripModel::query()
            ->sailingClub()
            ->where(function (Builder $query) use ($days) {
                $query->where('start_at', '>=', new \DateTime('-' . $days . ' days'));
            })
            ->get()
        ;

        $boats = [];
        foreach($boatTrips as $boatTrip){
            foreach($boatTrip->boats as $boatId => $qty){
                if(!isset($boats[$boatId])){
                    $boats[$boatId] = 0;
                }
                $boats[$boatId] += $qty;
            }
        }
        return $boats;
    }

    public function getNumberBoatTripsForDays(int $days = 90):array
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

        foreach ($boatTrips as $boatTrip){
            $reporting[$boatTrip->start_at->format('Y')][$boatTrip->start_at->format($formatKey)] += $boatTrip->totalBoats();
        }
        return $reporting;
    }

    public function getBoatTripGroupByHours(int $days = 90):array
    {
        $boatTrips = BoatTripModel::query()
            ->selectRaw('*, HOUR(start_at) as hour')
            ->sailingClub()
            ->where(function (Builder $query) use ($days) {
                $query->where('start_at', '>=', new \DateTime('-' . $days . ' days'));
            })
            ->whereNotNull('start_at')
            ->get();

        for($i=8; $i<20; $i++){
            $totalPerHour[$i] = 0;
        }

        foreach($boatTrips as $boatTrip){
            if(!isset($totalPerHour)){
                continue;
            }
            $totalPerHour[$boatTrip->hour] += $boatTrip->totalBoats();
        }
        ksort($totalPerHour);
        return $totalPerHour ?? [];
    }
}


