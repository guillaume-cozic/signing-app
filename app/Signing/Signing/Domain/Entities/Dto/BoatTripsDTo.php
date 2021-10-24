<?php


namespace App\Signing\Signing\Domain\Entities\Dto;


use App\Signing\Signing\Application\ViewModel\ReservationRowViewModel;
use Carbon\Carbon;

class BoatTripsDTo
{
    public function __construct(
        public string $id,
        public ?\DateTime $startAt = null,
        public ?\DateTime $endAt = null,
        public string $name = '',
        public ?array $boats = [],
        public float $hours = 0,
        public ?\DateTime $shouldStartAt = null,
        public ?bool $isMember = false,
        public ?bool $isInstructor = false,
        public ?bool $isReservation = false,
        public ?string $note = null,
        public ?string $sailorId = null,
    ){}

    public function startAt():?\DateTime
    {
        return $this->startAt;
    }

    public function toReservationRowViewModel():ReservationRowViewModel
    {
        return new ReservationRowViewModel(
            $this->id,
            $this->boats,
            Carbon::createFromFormat('Y-m-d H:i:s', $this->shouldStartAt->format('Y-m-d H:i:s')),
            $this->isMember,
            $this->isInstructor,
            $this->note,
            $this->hours,
            $this->name,
        );
    }
}
