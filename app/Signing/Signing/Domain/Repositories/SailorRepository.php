<?php

namespace App\Signing\Signing\Domain\Repositories;

use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Entities\State\SailorState;

interface SailorRepository
{
    public function getByName(string $name):?Sailor;
    public function save(SailorState $sailor);
}
