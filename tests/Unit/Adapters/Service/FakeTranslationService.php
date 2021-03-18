<?php


namespace Tests\Unit\Adapters\Service;


use App\Signing\Shared\Services\Translations\TranslationService;
use Illuminate\Support\Facades\App;

class FakeTranslationService implements TranslationService
{
    public function __construct(private array $translations = []){}

    public function get(string $key, string $resourceId, string $type):? string
    {
        return $this->translations[$type][$resourceId][$key][App::getLocale()] ?? null;
    }

    public function add(array $translations, string $resourceId, string $type)
    {
        $this->translations[$type][$resourceId] = $translations;
    }
}
