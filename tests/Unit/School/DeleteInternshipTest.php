<?php

namespace Tests\Unit\School;

use App\Signing\School\Domain\Model\Exception\InternshipNotFound;
use App\Signing\School\Domain\Model\Internship;
use App\Signing\School\Domain\UseCases\DeleteInternship;
use Tests\TestCase;

class DeleteInternshipTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotDeleteUnknownInternship()
    {
        self::expectException(InternshipNotFound::class);
        app(DeleteInternship::class)->execute('id');
    }

    /**
     * @test
     */
    public function shouldDeleteInternship()
    {
        $internship = new Internship('id', 7, 10, 10, []);
        $this->internshipRepository->save($internship);

        app(DeleteInternship::class)->execute('id');

        self::assertNull($this->internshipRepository->get('id'));

    }
}
