<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class SqlReadFleetRepository implements ReadFleetRepository
{
    public function all(int $page = 1, int $perPage = 10)
    {
        return FleetModel::query()
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
