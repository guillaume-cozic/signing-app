<?php

namespace Tests\Unit\School;

use App\Signing\School\Domain\Model\Exception\TraineesNumberCantBeNulOrNegativeException;
use App\Signing\School\Domain\Model\Internship;
use App\Signing\School\Domain\Model\InternshipState;
use App\Signing\School\Domain\UseCases\CreateInternshipSailing;
use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Exception\IntervalException;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use Tests\TestCase;

class CreateInternshipSailingTest extends TestCase
{
    private array $titleTranslations = [
        'fr' => 'Titre du stage',
        'en' => 'Title of the internship',
    ];

    /**
     * @test
     * @dataProvider incorrectDataAgeProvider
     */
    public function shouldNotCreateInternShipSailingWhenInvalidAges(?int $ageStart, ?int $ageEnd)
    {
        self::expectException(IntervalException::class);
        app(CreateInternshipSailing::class)->execute('id', $ageStart, $ageEnd, 10, [], $this->titleTranslations);
    }

    /**
     * @test
     * @dataProvider correctDataAgeProvider
     */
    public function shouldCreateInternShipSailingWithCorrectAges(?int $ageStart, ?int $ageEnd)
    {
        app(CreateInternshipSailing::class)->execute('id', $ageStart, $ageEnd, 10, [], $this->titleTranslations);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotCreateInternShipSailingWhenNumberTraineeInvalid()
    {
        self::expectException(TraineesNumberCantBeNulOrNegativeException::class);
        app(CreateInternshipSailing::class)->execute('id', 7, 8, -1, [], $this->titleTranslations);
    }

    /**
     * @test
     */
    public function shouldNotCreateInternShipSailingWithUnknownFleet()
    {
        $fleetId = 'fleet_id';
        self::expectException(FleetNotFound::class);
        app(CreateInternshipSailing::class)->execute('id', 7, 8, 10, [$fleetId], $this->titleTranslations);
    }

    /**
     * @test
     */
    public function shouldCreateInternShipSailing()
    {
        $internshipId = 'id';
        $fleetId = 'fleet_id';
        $fleet = new Fleet(new Id($fleetId), 10);
        $this->fleetRepository->save($fleet->getState());

        app(CreateInternshipSailing::class)->execute('id', 7, 8, 10, [$fleetId], $this->titleTranslations);
        $internshipExpected = new InternshipState($internshipId, 7, 8, 10, [$fleetId], $this->titleTranslations);

        $internship = $this->internshipRepository->get($internshipId);
        self::assertEquals($internshipExpected, $internship->getState());
    }

    public function correctDataAgeProvider(): array
    {
        return [
            [7, 8],
            [null, 8],
            [7, null]
        ];
    }

    public function incorrectDataAgeProvider(): array
    {
        return [
            [8, 7],
            [-5, -2],
            [-10, -2]
        ];
    }
}
