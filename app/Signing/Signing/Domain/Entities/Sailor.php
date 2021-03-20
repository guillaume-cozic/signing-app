<?php


namespace App\Signing\Signing\Domain\Entities;


use App\Signing\Signing\Domain\Entities\State\SailorState;

class Sailor implements HasState
{
    public function __construct(
        private ?string $memberId = '',
        private ?string $name = ''
    ){}

    public function getState(): SailorState
    {
        return new SailorState($this->name, $this->memberId);
    }
}
