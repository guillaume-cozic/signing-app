<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class SqlReadBoatTripRepository implements ReadBoatTripRepository
{
    public function getInProgress(?string $search = '', int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc")
    {
        return BoatTripModel::query()
            ->when(isset($search), function (Builder $query) use($search) {
                return //$query->join('fleet', 'boat_trip.boats->uuid', '=', 'fleet.uuid')
                    $query->where('boat_trip.name', 'LIKE', '%'.$search.'%');
                    //->orWhere('fleet.name->'.App::getLocale(), 'LIKE', '%'.$search.'%');
            })
            ->whereNull('end_at')
            ->sailingClub()
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function (BoatTripModel $item) {
                return $item->toDto();
            });
    }

}
