<?php

namespace App\Signing\Signing\Application\ViewModel;

use Carbon\Carbon;

class ReservationRowViewModel
{
    public int $total = 0;
    public array $messageListBoats;
    public string $color = 'indigo';
    public string $type = 'Réservation';
    public string $messageShouldStartAt;
    public string $messageBadgeSailorType;
    public string $messageBadgeSailorColor;

    public function __construct(
        public string $id,
        public array $boats,
        public Carbon $shouldStartAt,
        public bool $isMember,
        public bool $isInstructor,
        public ?string $note,
        public float $hours,
        public string $name,
    )
    {
        $this->boats();
        $this->startAtHumanReadable();
        $this->sailorType();
    }

    public function actions():array
    {
        return [
           'cancel' => [
               'route' => route('boat-trip.cancel', ['boatTripId' => $this->id]),
               'message' => 'Supprimer la réservation',
               'class' => 'btn-cancel fa fa-trash text-red p-1'
           ]
        ];
    }

    public function boats()
    {
        $total = 0;
        $this->messageListBoats = [];
        foreach ($this->boats as $boat => $qty){
            $this->messageListBoats[] = $qty. ' '.$boat;
            $total += $qty;
        }
        $this->total = $total;
    }

    public function startAtHumanReadable($locale = 'fr_FR')
    {
        setlocale(LC_TIME, $locale);
        $this->messageShouldStartAt = utf8_encode(strftime('%a %e %b &agrave; %H:%M', $this->shouldStartAt->getTimestamp()));
    }

    public function sailorType()
    {
        if($this->isMember){
            $this->messageBadgeSailorColor = 'primary';
            $this->messageBadgeSailorType = 'Adhérent';
        }
        if($this->isInstructor){
            $this->messageBadgeSailorColor = 'info';
            $this->messageBadgeSailorType = 'Moniteur';
        }
    }

}
