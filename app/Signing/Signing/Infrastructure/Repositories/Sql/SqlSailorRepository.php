<?php

namespace App\Signing\Signing\Infrastructure\Repositories\Sql;

use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Entities\State\SailorState;
use App\Signing\Signing\Domain\Repositories\SailorRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorModel;

class SqlSailorRepository implements SailorRepository
{
    public function getByName(string $name): ?Sailor
    {
        $sailor = SailorModel::query()->where('name', $name)->first();
        if(!isset($sailor)){
            return null;
        }
        return $sailor?->toState()?->toDomain()?->setSurrogateId($sailor->id);
    }

    public function save(SailorState $sailor)
    {
        $sailorModel = new SailorModel();
        $sailorModel->name = $sailor->name();
        $sailorModel->uuid = $sailor->sailorId();
        $sailorModel->save();
    }
}
