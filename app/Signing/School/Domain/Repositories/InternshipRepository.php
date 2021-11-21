<?php

namespace App\Signing\School\Domain\Repositories;

use App\Signing\School\Domain\Model\Internship;

interface InternshipRepository
{
    public function get(string $id):?Internship;
    public function save(Internship $internshipSailing);
}
