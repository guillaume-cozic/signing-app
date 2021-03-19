<?php


namespace App\Signing\Signing\Domain\Entities;


class Sailor
{
    public function __construct(
        private ?string $memberId = '',
        private ?string $name = ''
    ){}
}
