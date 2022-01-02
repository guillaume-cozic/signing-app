<?php

namespace App\Signing\School\Application\UseCases\Impl;

use App\Signing\School\Application\UseCases\PlanNewInternshipSession;
use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Model\InternshipSession;
use App\Signing\School\Domain\Model\SailingSession;
use App\Signing\School\Domain\Repositories\InternshipRepository;

class PlanNewSessionInternshipImpl implements PlanNewInternshipSession
{
    public function __construct(
        private InternshipRepository $internshipRepository
    ){}

    public function execute(string $id, array $sessions)
    {
        $internship = $this->internshipRepository->get($id);
        if(!isset($internship)) {
            throw new InternshipNotFound();
        }
        $sailingSessions = [];
        foreach($sessions as $session){
            $sailingSessions[] = new SailingSession($session['start'], $session['end']);
        }
        return new InternshipSession($sailingSessions);
    }
}
