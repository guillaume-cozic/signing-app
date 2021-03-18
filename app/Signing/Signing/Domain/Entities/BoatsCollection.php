<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;

class BoatsCollection
{
    public function __construct(private array $boats)
    {
        foreach($this->boats as $boatId => $qty){
            if($qty < 0) throw new NumberBoatsCantBeNegative('error.qty_cant_be_negative');
        }
    }

    public function quantity(string $boatId):int
    {
        return $this->boats[$boatId] ?? 0;
    }

    public function boats():array
    {
        return $this->boats;
    }
}
