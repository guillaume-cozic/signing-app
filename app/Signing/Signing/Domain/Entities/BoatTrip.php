<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use JetBrains\PhpStorm\Pure;

class BoatTrip
{
    private BoatTripRepository $boatTripRepository;

    public function __construct(
        private Id $id,
        private BoatTripDuration $boatTripDuration,
        private ?string $supportId = null,
        private ?int $qty = null,
        private ?string $name = null,
        private ?string $memberId = null,
    ){
        $this->boatTripRepository = app(BoatTripRepository::class);
    }

    #[Pure] public function id():string
    {
        return $this->id->id();
    }

    #[Pure] public function supportId():?string
    {
        return $this->supportId;
    }

    public function create()
    {
        $this->boatTripRepository->add($this);
    }

    public function end(\DateTime $endDate)
    {
        $this->boatTripDuration->end($endDate);
        $this->boatTripRepository->add($this);
    }

    public function quantity():int
    {
        return $this->qty;
    }

    public function toArray()
    {
        $boatTripDuration = $this->boatTripDuration->toArray();
        return array_merge([
            'uuid' => $this->id->id(),
            'support_id' => $this->supportId,
            'number_boats' => $this->qty,
            'name' => $this->name,
            'member_id' => $this->memberId,
        ], $boatTripDuration);
    }
}
