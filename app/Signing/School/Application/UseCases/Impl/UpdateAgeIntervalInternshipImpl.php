<?php

namespace App\Signing\School\Application\UseCases\Impl;

use App\Signing\School\Application\UseCases\UpdateAgeIntervalInternship;
use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Repositories\InternshipRepository;

class UpdateAgeIntervalInternshipImpl implements UpdateAgeIntervalInternship
{
    public function __construct(
        private InternshipRepository $internshipRepository
    ){}

    public function execute(string $id, int $ageStart, int $ageEnd)
    {
        $internship = $this->internshipRepository->get($id);
        if(!isset($internship)) {
            throw new InternshipNotFound();
        }
        $internship->updateAgeInterval($ageStart, $ageEnd);
    }
}
