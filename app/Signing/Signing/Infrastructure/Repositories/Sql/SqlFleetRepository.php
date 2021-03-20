<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Signing\Domain\Entities\Fleet;
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

    public function save(Fleet $s): void
    {
        $supportState = $s->getState();
        $supportModel = new FleetModel();
        $supportModel->total_available = $supportState->totalAvailable();
        $supportModel->uuid = $supportState->id();
        $supportModel->save();
    }
}
