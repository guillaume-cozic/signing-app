<?php


namespace App\Signing\Signing\Domain\Entities\Dto;


class FleetDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $totalAvailable,
        public string $state,
        public array $rents = [],
    ){}
}
