<?php

namespace App\Signing\School\Application\UseCases;

interface UpdateAgeIntervalInternship
{
    public function execute(string $id, int $ageStart, int $ageEnd);
}
