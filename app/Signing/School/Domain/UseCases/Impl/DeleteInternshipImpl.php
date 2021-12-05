<?php

namespace App\Signing\School\Domain\UseCases\Impl;

use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Repositories\InternshipRepository;
use App\Signing\School\Domain\UseCases\DeleteInternship;

class DeleteInternshipImpl implements DeleteInternship
{

    public function __construct(
        private InternshipRepository $internshipRepository
    ){}

    public function execute(string $id)
    {
        $internship = $this->internshipRepository->get($id);
        if(!isset($internship)){
            throw new InternshipNotFound();
        }
        $internship->delete();
    }
}
