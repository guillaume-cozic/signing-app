<?php


namespace App\Signing\Signing\Domain\Entities\Dto;


class FleetDto
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
