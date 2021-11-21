<?php

namespace Tests\Unit\Adapters\Repositories;

use App\Signing\School\Domain\Model\Internship;
use App\Signing\School\Domain\Repositories\InternshipRepository;

class InMemoryInternshipRepository implements InternshipRepository
{
    private array $internships = [];

    public function get(string $id): ?Internship
    {
        return isset($this->internships[$id]) ?  clone $this->internships[$id] : null;
    }

    public function save(Internship $internshipSailing)
    {
        $this->internships[$internshipSailing->id()] = $internshipSailing;
    }
}
