<?php

namespace Tests\Unit\School;

use App\Signing\School\Application\UseCases\PlanNewInternshipSession;
use App\Signing\School\Domain\Model\Exception\DateInThePast;
use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Model\Exception\OverlapDate;
use App\Signing\School\Domain\Model\Internship;
use App\Signing\School\Domain\Model\InternshipSession;
use App\Signing\School\Domain\Model\SailingSession;
use Carbon\Carbon;
use Tests\TestCase;

class PlanNewInternshipSessionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotPlanANewSessionWithUnknownInternship()
    {
        self::expectException(InternshipNotFound::class);
        app(PlanNewInternshipSession::class)->execute('abc', []);
    }

    /**
     * @test
     */
    public function shouldPlanNewInternshipSession()
    {
        $internship = new Internship('abc', 10, 15, 10, [], 10);
        $this->internshipRepository->save($internship);

        $sessions = [
            [
                'start' => $start1 = (new Carbon())->addDays(2)->setTime(9, 0)->getTimestamp(),
                'end' => $end1 = (new Carbon())->addDays(2)->setTime(14, 0)->getTimestamp()
            ],
            [
                'start' => $start2 = (new Carbon())->addDays(3)->setTime(9, 0)->getTimestamp(),
                'end' => $end2 = (new Carbon())->addDays(3)->setTime(14, 0)->getTimestamp()
            ],
        ];
        $sessionExpected = new InternshipSession([
            new SailingSession($start1, $end1),
            new SailingSession($start2, $end2),
        ]);
        $sessionSaved = app(PlanNewInternshipSession::class)->execute('abc', $sessions);
        self::assertEquals($sessionExpected, $sessionSaved);
    }

    /**
     * @test
     */
    public function shouldNotPlanNewInternshipSessionWithDateInThePast()
    {
        $internship = new Internship('abc', 10, 15, 10, [], 10);
        $this->internshipRepository->save($internship);

        $sessions= [
            [
                'start' => (new Carbon())->addDays(-2)->setTime(9, 0)->getTimestamp(),
                'end' => (new Carbon())->addDays(-2)->setTime(14, 0)->getTimestamp()
            ],
            [
                'start' => (new Carbon())->addDays(-3)->setTime(9, 0)->getTimestamp(),
                'end' => (new Carbon())->addDays(-3)->setTime(14, 0)->getTimestamp()
            ],
        ];
        self::expectException(DateInThePast::class);
        self::expectExceptionMessage('can_not_plan_internship_session_in_the_past');
        app(PlanNewInternshipSession::class)->execute('abc', $sessions);
    }

    /**
     * @test
     */
    public function shouldNotPlanNewInternshipSessionWithOverlapDate()
    {
        $internship = new Internship('abc', 10, 15, 10, [], 10);
        $this->internshipRepository->save($internship);

        $sessions = [
            [
                'start' => (new Carbon())->addDays(2)->setTime(9, 0)->getTimestamp(),
                'end' => (new Carbon())->addDays(2)->setTime(14, 0)->getTimestamp()
            ],
            [
                'start' => (new Carbon())->addDays(3)->setTime(9, 0)->getTimestamp(),
                'end' => (new Carbon())->addDays(3)->setTime(14, 0)->getTimestamp()
            ],
            [
                'start' => (new Carbon())->addDays(3)->setTime(9, 0)->getTimestamp(),
                'end' => (new Carbon())->addDays(3)->setTime(14, 0)->getTimestamp()
            ],
        ];
        self::expectException(OverlapDate::class);
        app(PlanNewInternshipSession::class)->execute('abc', $sessions);
    }
}
