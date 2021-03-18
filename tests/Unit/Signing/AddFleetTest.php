<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use Tests\TestCase;

class AddFleetTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddSupport()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20);

        $supportExpected = new Fleet(new Id($supportId), $totalSupportAvailable);
        $supportSaved = $this->fleetRepository->get($supportId);
        self::assertEquals($supportExpected, $supportSaved);
    }

    /**
     * @test
     */
    public function shouldNotAddSupportWhenQtyLtZero()
    {
        $this->identityProvider->add($supportId = 'abc');
        self::expectException(NumberBoatsCantBeNegative::class);
        self::expectExceptionMessage('error.qty_can_not_be_lt_0');
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = -1);
    }

    /**
     * @test
     */
    public function shouldAddSupportTranslation()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20);

        $translationsSaved = $this->translationService->get('title', $supportId, $type = 'support');
        self::assertEquals($title, $translationsSaved);

        $translationsSaved = $this->translationService->get('description', $supportId, $type = 'support');
        self::assertEquals($description, $translationsSaved);
    }
}
