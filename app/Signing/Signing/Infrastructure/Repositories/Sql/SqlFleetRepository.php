<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\FleetState;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class SqlFleetRepository implements FleetRepository
{
    public function __construct(private ContextService $contextService){}

    public function get(string $id):? Fleet
    {
        return FleetModel::query()
            ->where('uuid', $id)
            ->first()
            ?->toDomain();
    }

    public function save(FleetState $fleetState): void
    {
        $fleetModel = FleetModel::query()->where('uuid', $fleetState->id())->first() ?? new FleetModel();
        $fleetModel->total_available = $fleetState->totalAvailable();
        $fleetModel->uuid = $fleetState->id();
        $fleetModel->state = $fleetState->state();
        $fleetModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $fleetModel->rental_rate = $fleetState->rentalRate();
        $fleetModel->save();
    }
}
