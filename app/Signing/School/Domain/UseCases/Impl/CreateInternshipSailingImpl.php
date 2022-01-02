<?php

namespace App\Signing\School\Domain\UseCases\Impl;

use App\Signing\School\Domain\Model\Internship;
use App\Signing\School\Domain\UseCases\CreateInternshipSailing;

class CreateInternshipSailingImpl implements CreateInternshipSailing
{
    public function execute(string $id, ?float $startAge, ?float $endAge, int $numberTraines, array $title, array $fleets = [])
    {
        $internship = new Internship($id, $startAge, $endAge, $numberTraines, $fleets);
        $internship->save($title);
    }
}
