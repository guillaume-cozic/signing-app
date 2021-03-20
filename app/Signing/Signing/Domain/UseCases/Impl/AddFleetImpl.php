<?php


namespace App\Signing\Signing\Domain\UseCases\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\Translations\TranslationService;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use Illuminate\Support\Facades\App;

class AddFleetImpl implements AddFleet
{
    public function __construct(private TranslationService $translationService){}

    public function execute(string $title, string $description, int $totalAvailable)
    {
        (new Fleet($id = new Id(), $totalAvailable))->create($title, $description);
    }
}
