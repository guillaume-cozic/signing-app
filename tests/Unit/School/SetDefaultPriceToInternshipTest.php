<?php

namespace Tests\Unit\School;

use App\Signing\School\Application\UseCases\SetDefaultPriceToInternship;
use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Model\Exception\PriceCantBeNegative;
use App\Signing\School\Domain\Model\Internship;
use Tests\TestCase;

class SetDefaultPriceToInternshipTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotUpdateDefaultPriceUnknownInternship()
    {
        self::expectException(InternshipNotFound::class);
        app(SetDefaultPriceToInternship::class)->execute('abc', 15);
    }

    /**
     * @test
     */
    public function shouldUpdateDefaultPrice()
    {
        $internship = new Internship('abc', 10, 15, 10, []);
        $this->internshipRepository->save($internship);

        app(SetDefaultPriceToInternship::class)->execute('abc', 10);

        $internshipExpected = new Internship('abc', 10, 15, 10, [], 10);
        $internshipSaved = $this->internshipRepository->get('abc');

        self::assertEquals($internshipExpected, $internshipSaved);
    }

    /**
     * @test
     */
    public function shouldNotUpdateDefaultPriceWithNegativeOrNilValue()
    {
        $internship = new Internship('abc', 10, 15, 10, []);
        $this->internshipRepository->save($internship);

        self::expectException(PriceCantBeNegative::class);
        app(SetDefaultPriceToInternship::class)->execute('abc', -1);
    }
}
