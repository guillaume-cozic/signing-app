<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Models\User;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTrip;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTripState;
use App\Signing\Signing\Domain\Entities\BoatTrip\Reservation;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorModel;

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
        $sailor = SailorModel::query()->where('uuid', $boatTripState->sailorId())->first() ?? null;

        $boatTripModel->uuid = $boatTripState->id();
        $boatTripModel->boats = $boatTripState->boats();
        $boatTripModel->name = $boatTripState->name();
        $boatTripModel->member_id = isset($member) ? $member->id : null;
        $boatTripModel->number_hours = $boatTripState->numberHours();
        $boatTripModel->end_at = $boatTripState->endAt();
        $boatTripModel->start_at = $boatTripState->startAt();
        $boatTripModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $boatTripModel->should_start_at = $boatTripState->shouldStartAt();
        $boatTripModel->is_member = $boatTripState->isMember();
        $boatTripModel->is_instructor = $boatTripState->isInstructor();
        $boatTripModel->is_reservation = $boatTripState->isReservation();
        $boatTripModel->note = $boatTripState->note();
        $boatTripModel->sailor_id = $sailor->id ?? null;
        $boatTripModel->options = $boatTripState->options() ?? [];
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

    public function getUsedBoat(string $boatId, \DateTime $startAt, \DateTime $provisionalEndDate): array
    {
        $startAt = $startAt->getTimestamp();
        $provisionalEndDate = $provisionalEndDate->getTimestamp();
        return BoatTripModel::query()
            ->selectRaw('
                *,
                UNIX_TIMESTAMP(DATE_ADD(IF(should_start_at is not null, should_start_at, start_at), INTERVAL number_hours HOUR)) as boatTripEndDate,
                UNIX_TIMESTAMP(IF(should_start_at is not null, should_start_at, start_at)) as boatTripStartDate
            ')
            ->whereNotNull('boats->'.$boatId)
            ->sailingClub()
            ->havingRaw('
                (? >= boatTripStartDate && ? <= boatTripEndDate) ||
                (? >= boatTripStartDate && ? <= boatTripEndDate) ||
                (? <= boatTripStartDate && ? >= boatTripEndDate)'
                , [$startAt, $startAt, $provisionalEndDate, $provisionalEndDate, $startAt, $provisionalEndDate])
            ->whereNull('end_at')
            ->get()
            ?->transform(function (BoatTripModel $model){
                return $model->toDomain();
            })
            ->toArray();
    }

    public function getReservation(string $id): ?Reservation
    {
        return BoatTripModel::query()
            ->with('member')
            ->where('uuid', $id)
            ->first()
            ?->toReservationDomain();
    }


}
