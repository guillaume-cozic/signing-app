<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SupportModel;

class SqlBoatTripRepository implements BoatTripRepository
{
    public function get(string $id): ?BoatTrip
    {
        return BoatTripModel::with('support')
            ->where('uuid', $id)->first()?->toDomain();
    }

    public function add(BoatTrip $b)
    {
        $model = new BoatTripModel();

        if($b->supportId() !== null) {
            $supportModel = SupportModel::where('uuid', $b->supportId())->first();
            $model->support_id = $supportModel?->id;
        }

        $model->fill($b->toArray());
        $model->save();
    }

    public function getBySupport(string $supportId): array
    {
        // TODO: Implement getBySupport() method.
    }

}
