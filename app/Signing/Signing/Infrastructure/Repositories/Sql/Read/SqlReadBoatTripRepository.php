<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class SqlReadBoatTripRepository implements ReadBoatTripRepository
{
    public function getInProgress(?string $search = '', int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = [])
    {
        $fleets = [];
        if(!empty($search)){
            $fleets = FleetModel::query()
                ->where('fleet.name->'.App::getLocale(), 'LIKE', '%'.$search.'%')
                ->get()
                ->pluck('uuid')->toArray();
        }
        return BoatTripModel::query()
            ->selectRaw('*, UNIX_TIMESTAMP(start_at) + 3600 * number_hours as should_return')
            ->when(isset($search) && $search !== '', function (Builder $query) use($search, $fleets) {
                $query->where('boat_trip.name', 'LIKE', '%'.$search.'%');
                foreach($fleets as $fleet) {
                    $query->orWhereNotNull('boats->'.$fleet);
                }
                return $query;
            })
            ->when(!isset($filters['ended']), function (Builder $query) {
                return $query->whereNull('end_at');
            })
            ->when(isset($filters['ended']), function (Builder $query) {
                return $query->whereNotNull('end_at')
                    ->whereRaw('date(end_at) = ?', (new \DateTime())->format('Y-m-d'));
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

}
