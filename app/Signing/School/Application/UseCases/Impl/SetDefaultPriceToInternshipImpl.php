<?php

namespace App\Signing\School\Application\UseCases\Impl;

use App\Signing\School\Application\UseCases\SetDefaultPriceToInternship;
use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Repositories\InternshipRepository;

class SetDefaultPriceToInternshipImpl implements SetDefaultPriceToInternship
{
    public function __construct(
        private InternshipRepository $internshipRepository
    ){}

    public function execute(string $id, float $price)
    {
        $internship = $this->internshipRepository->get($id);
        if(!isset($internship)) {
            throw new InternshipNotFound();
        }
        $internship->setDefaultPrice($price);
    }
}
