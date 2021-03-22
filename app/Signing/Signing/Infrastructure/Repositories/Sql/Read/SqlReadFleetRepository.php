<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class SqlReadFleetRepository implements ReadFleetRepository
{
    public function all()
    {
        return FleetModel::all()
            ->transform(function (FleetModel $item) {
                return $item->toDto();
            });
    }
}
