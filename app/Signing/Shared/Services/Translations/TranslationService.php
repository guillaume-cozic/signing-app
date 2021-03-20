<?php


namespace App\Signing\Shared\Services\Translations;


interface TranslationService
{
    public function get(string $key, string $resourceId, string $type):?string;
    public function add(array $translations, string $resourceId, string $type);
}
