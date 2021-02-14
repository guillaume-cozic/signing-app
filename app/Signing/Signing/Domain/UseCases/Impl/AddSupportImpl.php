<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\Translations\TranslationService;
use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\UseCases\AddSupport;
use Illuminate\Support\Facades\App;

class AddSupportImpl implements AddSupport
{
    public function __construct(private TranslationService $translationService){}

    public function execute(string $title, string $description, int $totalAvailable)
    {
        (new Support($id = new Id(), $totalAvailable))->create();

        $this->saveTranslations($title, $description, $id);
    }

    private function saveTranslations(string $title, string $description, Id $id): void
    {
        $trans = [
            'title' => [App::getLocale() => $title],
            'description' => [App::getLocale() => $description],
        ];
        $this->translationService->add($trans, $id->id(), 'support');
    }
}
