<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\Translations\TranslationService;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\Pure;

class Fleet implements HasState
{
    private FleetRepository $fleetRepository;
    private TranslationService $translationService;

    public function __construct(
        private Id $id,
        private int $totalAvailable
    )
    {
        if($this->totalAvailable < 0) throw new NumberBoatsCantBeNegative('error.qty_can_not_be_lt_0');
        $this->fleetRepository = app(FleetRepository::class);
        $this->translationService = app(TranslationService::class);
    }

    #[Pure] public function id():string
    {
        return $this->id->id();
    }

    public function create(string $title, string $description)
    {
        $this->fleetRepository->save($this);

        $trans = [
            'title' => [App::getLocale() => $title],
            'description' => [App::getLocale() => $description],
        ];
        $this->translationService->add($trans, $this->id(), 'support');
    }

    public function update(int $totalAvailable)
    {
        if($totalAvailable < 0) throw new NumberBoatsCantBeNegative('error.qty_can_not_be_lt_0');
        $this->totalAvailable = $totalAvailable;
        $this->fleetRepository->save($this);
    }

    public function isBoatAvailable(int $qtyAsked):bool
    {
        return $this->totalAvailable >= $qtyAsked;
    }

    public function getState(): FleetState
    {
        return new FleetState($this->id->id(), $this->totalAvailable);
    }
}
