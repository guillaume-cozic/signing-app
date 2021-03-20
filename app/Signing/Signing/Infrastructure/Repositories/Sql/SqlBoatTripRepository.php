<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTripState;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class SqlBoatTripRepository implements BoatTripRepository
{
    public function get(string $id): ?BoatTrip
    {
        return BoatTripModel::query()
            ->where('uuid', $id)
            ->first()
            ?->toDomain();
    }

    public function save(BoatTripState $boatTripState)
    {
        $boatTripModel = BoatTripModel::query()->where('uuid', $boatTripState->id())->first() ?? new BoatTripModel();;

        $boatTripModel->uuid = $boatTripState->id();
        $boatTripModel->boats = $boatTripState->boats();
        $boatTripModel->name = $boatTripState->name();
        $boatTripModel->member_id = $boatTripState->memberId();
        $boatTripModel->number_hours = $boatTripState->numberHours();
        $boatTripModel->end_at = $boatTripState->endAt();
        $boatTripModel->start_at = $boatTripState->startAt();
        $boatTripModel->save();
    }

    public function getInProgressByBoat(string $boatId): array
    {
        return BoatTripModel::query()
            ->whereHas('support', function ($query) use($boatId){
                return $query->where('uuid', $boatId);
            })
            ->get()
            ?->transform(function (BoatTripModel $model){
                return $model->toDomain();
            })
            ->toArray();
    }

}
