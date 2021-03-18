<?php


namespace App\Signing\Shared\Services\Translations;


use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;

class TranslationServiceImpl implements TranslationService
{
    public function get(string $key, string $resourceId, string $type): ?string
    {
        if ($type === 'support') {
            return FleetModel::query()->where('uuid', $resourceId)->first()?->$key;
        }
    }

    public function add(array $translations, string $resourceId, string $type)
    {
        if($type === 'support'){
            FleetModel::query()->where('uuid', $resourceId)->update($translations);
        }
    }
}
