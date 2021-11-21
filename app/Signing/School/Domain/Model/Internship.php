<?php

namespace App\Signing\School\Domain\Model;

use App\Signing\School\Domain\Model\Exception\TraineesNumberCantBeNulOrNegativeException;
use App\Signing\School\Domain\Repositories\InternshipRepository;
use App\Signing\Shared\Entities\HasState;
use App\Signing\Shared\ValueObject\Interval;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;

class Internship implements HasState
{
    private FleetCollection $fleets;
    private Interval $ages;

    public function __construct(
        private string $id,
        private ?int $startAge,
        private ?int $endAge,
        private int $maxTrainees,
        array $fleetsCollection
    ){
        if($this->maxTrainees <= 0){
            throw new TraineesNumberCantBeNulOrNegativeException();
        }
        $this->ages = new Interval($startAge, $endAge);
        $this->fleets = new FleetCollection($fleetsCollection);
    }

    public function id():string
    {
        return $this->id;
    }

    public function save(array $title)
    {
        $this->title = $title;
        app(InternshipRepository::class)->save($this);
    }

    public function getState(): InternshipState
    {
        return new InternshipState(
            $this->id,
            $this->startAge,
            $this->endAge,
            $this->maxTrainees,
            $this->fleets->toArray(),
            $this->title
        );
    }


}
