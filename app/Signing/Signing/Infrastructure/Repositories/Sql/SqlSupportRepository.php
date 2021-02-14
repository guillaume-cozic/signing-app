<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\Repositories\SupportRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SupportModel;

class SqlSupportRepository implements SupportRepository
{
    public function get(string $id): ?Support
    {

    }

    public function save(Support $s): void
    {
        $supportModel = new SupportModel();
        $supportModel->fill($s->toArray());
        $supportModel->save();
    }
}
