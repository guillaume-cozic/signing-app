<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Repositories\SupportRepository;
use JetBrains\PhpStorm\Pure;

class Support
{
    private SupportRepository $supportRepository;

    public function __construct(
        private Id $id,
        private int $totalAvailable
    )
    {
        $this->supportRepository = app(SupportRepository::class);
    }

    public function create()
    {
        $this->supportRepository->save($this);
    }

    #[Pure] public function id():string
    {
        return $this->id->id();
    }

    #[Pure] public function totalAvailable():int
    {
        return $this->totalAvailable;
    }

    public function toArray():array
    {
        return [
            'uuid' => $this->id->id(),
            'total_available' => $this->totalAvailable
        ];
    }
}
