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
        private string $id,
        private array $boats,
        private Carbon $shouldStartAt,
        private bool $isMember,
        private bool $isInstructor,
        private string $note,
    )
    {
        $this->boats();
        $this->startAtHumanReadable();
        $this->sailorType();
    }

    public function actions()
    {
        return [
           'cancel' => [
               'route' => route('boat-trip.cancel', ['boatTripId' => $this->id])
           ]
        ];
    }

    public function boats()
    {
        $total = 0;
        $this->messageListBoats = [];
        foreach ($this->boats as $qty => $boat){
            $this->messageListBoats[] = $qty. ' '.$boat;
            $total += $qty;
        }
        $this->total = $total;
    }

    public function startAtHumanReadable($locale = 'fr_FR')
    {
        setlocale(LC_TIME, $locale);
        $this->messageShouldStartAt = utf8_encode(strftime('%a %e %b à %H:%M', $this->shouldStartAt->getTimestamp()));
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
