<?php


namespace Tests\Unit\Adapters\Service;


use App\Signing\Shared\Services\Translations\TranslationService;

class FakeTranslationService implements TranslationService
{
    public function __construct(private array $translations = []){}

    public function get(string $key, string $resourceId, string $type):? array
    {
        return $this->translations[$type][$resourceId][$key] ?? null;
    }

    public function add(array $translations, string $resourceId, string $type)
    {
        $this->translations[$type][$resourceId] = $translations;
    }
}
