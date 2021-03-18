<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class SqlBoatTripRepository implements BoatTripRepository
{
    public function get(string $id): ?BoatTrip
    {
        return BoatTripModel::with('support')
            ->where('uuid', $id)
            ->first()
            ?->toDomain();
    }

    public function add(BoatTrip $b)
    {
        $boatTripState = $b->getState();
        $boatTripModel = new BoatTripModel();

        if($boatTripState->supportId() !== null) {
            $boatTripModel->support_id = FleetModel::where('uuid', $boatTripState->supportId())->first()?->id;
        }

        $boatTripModel->uuid = $boatTripState->id();
        $boatTripModel->number_boats = $boatTripState->qty();
        $boatTripModel->name = $boatTripState->name();
        $boatTripModel->fill($boatTripState->duration());
        $boatTripModel->save();
    }

    public function getBySupport(string $supportId): array
    {
        return BoatTripModel::query()
            ->whereHas('support', function ($query) use($supportId){
                return $query->where('uuid', $supportId);
            })
            ->get()
            ?->transform(function (BoatTripModel $model){
                return $model->toDomain();
            })
            ->toArray();
    }

}
