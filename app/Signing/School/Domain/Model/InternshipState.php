<?php

namespace App\Signing\School\Domain\Model;

use App\Signing\Shared\Entities\State;

class InternshipState implements State
{
    public function __construct(
        private string $id,
        private ?int $startAge,
        private ?int $endAge,
        private int $maxTrainees,
        private array $fleetsCollection,
        private array $title
    ){}
}
