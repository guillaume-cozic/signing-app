<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class SqlReadFleetRepository implements ReadFleetRepository
{
    public function search(?array $search = [], int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc")
    {
        return FleetModel::query()
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
            ->sailingClub()
            ->paginate($perPage, ['*'], 'page', $page)
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
            ->get();
        $fleets->transform(function ($item){
           $boatTrips = BoatTripModel::query()
               ->whereNull('end_at')
               ->sailingClub()
               ->whereNotNull('boats->'.$item->uuid)->get();
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
