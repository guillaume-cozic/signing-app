<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class SqlReadFleetRepository implements ReadFleetRepository
{
    public function search(?array $search = [], int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc")
    {
        $queryFleets = FleetModel::query()
            ->when(!empty($search['search']), function (Builder $query) use($search){
                $query->where('total_available', 'LIKE', '%'.$search['search'].'%')
                    ->orWhereRaw('lower(name->\'$.' . App::getLocale().'\') LIKE ?', '%'.strtolower($search['search']).'%')
                    ->orWhere('state', 'LIKE', '%'.$search['search'].'%');
            })
            ->when(!empty($search['filters']['state']), function (Builder $query) use($search) {
                $query->where('state', $search['filters']['state']);
            })
            ->when(isset($sort), function (Builder $query) use($sort, $dirSort){
                $sort = $sort === 'name' ? 'name->'.App::getLocale() : $sort;
                $query->orderBy($sort, $dirSort);
            })
            ->sailingClub();

            if($perPage === 0) {
                return $queryFleets->get()
                    ->transform(function (FleetModel $item) {
                        return $item->toDto();
                    });
            }

            return $queryFleets->paginate($perPage, ['*'], 'page', $page)
            ->through(function (FleetModel $item) {
                return $item->toDto();
            });
    }

    public function getById(string $id)
    {
        return FleetModel::where('uuid', $id)->first()?->toDto();
    }

    public function getNumberAvailableBoats():array
    {
        $fleets = FleetModel::query()
            ->sailingClub()
            ->where('state', Fleet::STATE_ACTIVE)
            ->orderByRaw('name->\'$.' . App::getLocale().'\' asc')
            ->get();
        $fleets->transform(function ($item){
           $boatTrips = BoatTripModel::query()
               ->where(function ($query) {
                   $query->whereRaw('date(start_at) = date(now())')
                        ->orWhereRaw('date(should_start_at) = date(now())');
               })
               ->whereNull('end_at')
               ->where(function ($query){
                    return $query->whereRaw('UNIX_TIMESTAMP(should_start_at) - UNIX_TIMESTAMP(now()) <= ?', 60*60)
                        ->orWhereRaw('UNIX_TIMESTAMP(start_at) - UNIX_TIMESTAMP(now()) <= ?', 60*60);
               })
               ->sailingClub()
               ->whereNotNull('boats->'.$item->uuid)
               ->get();
           $qtyUsed = 0;
           foreach ($boatTrips as $boatTrip){
               $qtyUsed += $boatTrip->boats[$item->uuid];
           }

           return [
               'id' => $item->uuid,
               'name' => $item->name,
               'available' => $item->total_available - $qtyUsed,
               'total' => $item->total_available
           ];
        });
        return $fleets->toArray();
    }
}
