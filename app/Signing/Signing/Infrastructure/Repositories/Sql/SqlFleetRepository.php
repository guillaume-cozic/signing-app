<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\FleetState;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class SqlFleetRepository implements FleetRepository
{
    public function get(string $id):? Fleet
    {
        return FleetModel::query()
            ->where('uuid', $id)
            ->first()
            ?->toDomain();
    }

    public function save(FleetState $fleetState): void
    {
        $supportModel = new FleetModel();
        $supportModel->total_available = $fleetState->totalAvailable();
        $supportModel->uuid = $fleetState->id();
        $supportModel->save();
    }
}
