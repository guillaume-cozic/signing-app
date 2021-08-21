<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SqlReadBoatTripRepository implements ReadBoatTripRepository
{
    public function getInProgress(?string $search = '', int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = [])
    {
        $fleets = [];
        if(!empty($search)){
            $fleets = FleetModel::query()
                ->sailingClub()
                ->whereRaw('lower(fleet.name->\'$.'.App::getLocale().'\') LIKE ?', '%'.strtolower($search).'%')
                ->get()
                ->pluck('uuid')->toArray();
        }

        return BoatTripModel::query()
            ->when(isset($filters['reservations']) && $filters['reservations'] === true, function ($query){
                $query->whereRaw('date(should_start_at) != date(now())')
                ->whereNull('start_at')
                ->whereRaw('UNIX_TIMESTAMP(should_start_at) > UNIX_TIMESTAMP(now())');
            })
            ->when(!isset($filters['reservations']) || $filters['reservations'] === false, function ($query){
                return $query->where(function($query){
                    return $query->whereRaw('date(should_start_at) = date(now())')
                        ->orWhereRaw('date(start_at) = date(now())');
                });
            })
            ->selectRaw('*, UNIX_TIMESTAMP(start_at) + 3600 * number_hours as should_return')
            ->when(isset($search) && $search !== '', function (Builder $query) use($search, $fleets) {
                return $query->where(function ($query) use($search, $fleets){
                    $query->whereRaw('lower(boat_trip.name) LIKE ?', '%' . strtolower($search) . '%');
                    $query->orWhere(function ($query) use ($fleets) {
                        foreach ($fleets as $fleet) {
                            $query->orWhereNotNull('boats->' . $fleet);
                        }
                    });
                });
            })
            ->when(!isset($filters['ended']), function (Builder $query) {
                return $query->whereNull('end_at');
            })
            ->when(isset($filters['ended']), function (Builder $query) {
                return $query->whereNotNull('end_at')
                    ->whereRaw('date(end_at) = date(now())')
                ;
            })
            ->sailingClub()
            ->when(isset($sort), function (Builder $query) use($sort, $dirSort){
                return $query->orderBy($sort, $dirSort);
            })
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function (BoatTripModel $item) {
                return $item->toDto();
            });
    }

    public function getNearToFinishOrStart(): array
    {
        return BoatTripModel::query()
            ->where(function ($query) {
                return $query->where(function ($query) {
                    return $query->whereRaw('ABS(UNIX_TIMESTAMP(NOW()) - (UNIX_TIMESTAMP(start_at) + 60*60 * number_hours)) <= ?', 5 * 60)
                        ->orWhereRaw('UNIX_TIMESTAMP(start_at) + 60*60 * number_hours <  UNIX_TIMESTAMP(NOW())');
                    })
                    ->whereNull('end_at');
            })
            ->orWhere(function ($query) {
                return $query->where(function ($query){
                    return $query->whereRaw('ABS(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(should_start_at)) <= ?', 5 * 60)
                    ->orWhereRaw('UNIX_TIMESTAMP(should_start_at) < UNIX_TIMESTAMP(NOW())');
                })
                ->whereNull('start_at');
            })
            ->sailingClub()
            ->get()
            ->transform(function (BoatTripModel $item) {
                return $item->toDto();
            })
            ?->toArray()
        ;
    }

    public function getNotClosedYesterdayOrMore()
    {
        return BoatTripModel::query()
            ->whereNull('end_at')
            ->where(function ($query) {
                return $query->where(function ($query){
                    return $query->where(function ($query) {
                        $query->whereRaw('date(should_start_at) != date(now())')
                            ->whereRaw('UNIX_TIMESTAMP(should_start_at) < UNIX_TIMESTAMP(now())');
                    })
                    ->orWhere(function($query){
                        $query->whereRaw('date(start_at) != date(now())')
                            ->whereRaw('UNIX_TIMESTAMP(start_at) < UNIX_TIMESTAMP(now())');
                    });
                });
            })
            ->sailingClub()
            ->get()
            ->transform(function (BoatTripModel $item) {
                return $item->toDto();
            })
            ?->toArray()
        ;
    }

}
