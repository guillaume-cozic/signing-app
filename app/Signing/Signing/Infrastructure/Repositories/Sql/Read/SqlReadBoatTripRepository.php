<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;

class SqlReadBoatTripRepository implements ReadBoatTripRepository
{
    public function getActive()
    {
        return BoatTripModel::with('support')
            ->whereNull('end_at')
            ->paginate()
            ->transform(function (BoatTripModel $item){
                return $item->toDto();
            });
    }

}
