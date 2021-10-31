<?php

namespace App\Signing\Signing\Infrastructure\Repositories\Sql;

use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Entities\State\SailorState;
use App\Signing\Signing\Domain\Repositories\SailorRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorModel;

class SqlSailorRepository implements SailorRepository
{
    public function __construct(private ContextService $contextService){}

    public function getByName(string $name): ?Sailor
    {
        $sailorModel = SailorModel::query()->where('name', $name)->first();
        if(!isset($sailorModel)){
            return null;
        }
        $sailor = $sailorModel->toState()->toDomain();
        $sailor->setSurrogateId($sailorModel->id);
        return $sailor;
    }

    public function save(SailorState $sailor)
    {
        $sailorModel = new SailorModel();
        $sailorModel->name = $sailor->name();
        $sailorModel->uuid = $sailor->sailorId();
        $sailorModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $sailorModel->save();
    }
}
