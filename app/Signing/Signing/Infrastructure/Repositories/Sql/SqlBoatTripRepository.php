<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Models\User;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTripState;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;

class SqlBoatTripRepository implements BoatTripRepository
{
    public function __construct(private ContextService $contextService){}

    public function get(string $id): ?BoatTrip
    {
        return BoatTripModel::query()
            ->with('member')
            ->where('uuid', $id)
            ->first()
            ?->toDomain();
    }

    public function save(BoatTripState $boatTripState)
    {
        $boatTripModel = BoatTripModel::query()->where('uuid', $boatTripState->id())->first() ?? new BoatTripModel();;
        $member = User::query()->where('uuid', $boatTripState->memberId())->first() ?? null;

        $boatTripModel->uuid = $boatTripState->id();
        $boatTripModel->boats = $boatTripState->boats();
        $boatTripModel->name = $boatTripState->name();
        $boatTripModel->member_id = isset($member) ? $member->id : null;
        $boatTripModel->number_hours = $boatTripState->numberHours();
        $boatTripModel->end_at = $boatTripState->endAt();
        $boatTripModel->start_at = $boatTripState->startAt();
        $boatTripModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $boatTripModel->should_start_at = $boatTripState->shouldStartAt();
        $boatTripModel->save();
    }

    public function getInProgressByBoat(string $boatId): array
    {
        return BoatTripModel::query()
            ->whereNotNull('boats->'.$boatId)
            ->sailingClub()
            ->whereNull('end_at')
            ->get()
            ?->transform(function (BoatTripModel $model){
                return $model->toDomain();
            })
            ->toArray();
    }

    public function delete(string $boatTripId)
    {
        return BoatTripModel::query()
            ->where('uuid', $boatTripId)
            ->delete();
    }
}
