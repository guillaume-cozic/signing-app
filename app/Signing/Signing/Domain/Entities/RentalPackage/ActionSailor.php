<?php declare(strict_types=1);

namespace App\Signing\Signing\Domain\Entities\RentalPackage;


use Carbon\Carbon;

class ActionSailor implements \JsonSerializable
{
    CONST ADD_HOURS = 'add';
    CONST SUB_HOURS = 'sub';
    CONST SAIL_HOURS = 'sail';

    public function __construct(
        private string $type,
        private float $hours,
        private Carbon $atTime,
    ){
        $this->hours = abs($this->hours);
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'hours' => $this->hours,
            'at_time' => $this->atTime->format('Y-m-d H:i'),
        ];
    }
}
