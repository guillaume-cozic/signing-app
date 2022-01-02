<?php

namespace Tests\Unit\School;

use App\Signing\School\Application\UseCases\UpdateAgeIntervalInternship;
use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Model\Internship;
use App\Signing\Shared\Exception\IntervalException;
use Tests\TestCase;

class UpdateAgeIntervalInternshipTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotUpdateUnknownInternship()
    {
        self::expectException(InternshipNotFound::class);
        app(UpdateAgeIntervalInternship::class)->execute('id', 10, 15);
    }

    /**
     * @test
     */
    public function shouldUpdateAgeIntervalInternship()
    {
        $internship = new Internship('abc', 10, 15, 10, []);
        $this->internshipRepository->save($internship);

        app(UpdateAgeIntervalInternship::class)->execute('abc', 11, 14);

        $internshipExpected = new Internship('abc', 11, 14, 10, []);
        $internshipSaved = $this->internshipRepository->get('abc');
        self::assertEquals($internshipExpected, $internshipSaved);
    }

    /**
     * @test
     */
    public function shouldNotUpdateAgeIntervalInternshipWithInvalidValue()
    {
        $internship = new Internship('abc', 10, 15, 10, []);
        $this->internshipRepository->save($internship);

        self::expectException(IntervalException::class);
        app(UpdateAgeIntervalInternship::class)->execute('abc', 11, 10);
    }
}
