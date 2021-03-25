<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class SqlReadFleetRepository implements ReadFleetRepository
{
    public function search(?string $search = '', int $page = 1, int $perPage = 10)
    {
        return FleetModel::query()
            ->when($search !== '', function (Builder $query) use($search){
                $query->where('total_available', 'LIKE', '%'.$search.'%')
                    ->orWhere('name->' . App::getLocale(), 'LIKE', '%'.$search.'%');
                })
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function (FleetModel $item) {
                return $item->toDto();
            });
    }

    public function getById(string $id)
    {
        return FleetModel::where('uuid', $id)->first()?->toDto();
    }
}