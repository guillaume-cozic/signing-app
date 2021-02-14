<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;

class SqlReadBoatTripRepository implements ReadBoatTripRepository
{
    public function getActive()
    {
        $records = BoatTripModel::with('support')
            ->whereNull('end_at')
            ->paginate()
            ->transform(function ($item, $key){
                return new BoatTripsDTo(
                    $item->uuid,
                    $item->uuid,
                );
            });

        return $records;
    }
}
