<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use Tests\TestCase;

class AddFleetTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddFleet()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddFleet::class)->execute($title = 'hobie cat 15', 'desc', $totalSupportAvailable = 20, $state = Fleet::STATE_ACTIVE);

        $supportExpected = new Fleet(new Id($supportId), $totalSupportAvailable, $state);
        $supportSaved = $this->fleetRepository->get($supportId);
        self::assertEquals($supportExpected, $supportSaved);
    }

    /**
     * @test
     */
    public function shouldAddFleetWithInactiveState()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddFleet::class)->execute($title = 'hobie cat 15', 'desc', $totalSupportAvailable = 20, $state = Fleet::STATE_INACTIVE);

        $supportExpected = new Fleet(new Id($supportId), $totalSupportAvailable, $state);
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
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = -1, Fleet::STATE_INACTIVE);
    }

    /**
     * @test
     */
    public function shouldAddSupportTranslation()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20, Fleet::STATE_INACTIVE);

        $translationsSaved = $this->translationService->get('name', $supportId, $type = 'support');
        self::assertEquals($title, $translationsSaved);
    }


    /**
     * @test
     */
    public function shouldNotAddFleetWithSameNameTwice()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20, Fleet::STATE_INACTIVE);

        self::expectException(FleetAlreadyExist::class);
        app(AddFleet::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20, Fleet::STATE_INACTIVE);
    }
}
