<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetState;
use App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Exceptions\NumberBoatsCantBeNegative;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;
use Tests\TestCase;

class UpdateFleetTest extends TestCase
{
    private UpdateFleet $updateFleet;

    public function setUp(): void
    {
        parent::setUp();
        $this->updateFleet = app(UpdateFleet::class);
    }

    /**
     * @test
     */
    public function shouldUpdateFleet()
    {
        $fleetId = 'abc';
        $fleet = new Fleet(new Id($fleetId), 15);
        $this->fleetRepository->save($fleet->getState());


        $title = 'hobie cat';
        $this->updateFleet->execute($fleetId, $newTotal = 20, $title, Fleet::STATE_INACTIVE);

        $fleetUpdated = $this->fleetRepository->get($fleetId);
        $fleetExpected = new Fleet(new Id($fleetId), $newTotal, Fleet::STATE_INACTIVE);
        self::assertEquals($fleetExpected, $fleetUpdated, 'Fleet has not been updated');
    }

    /**
     * @test
     */
    public function shouldNotUpdateFleetWhenQtyNegative()
    {
        $fleetId = 'abc';
        $fleet = new Fleet(new Id($fleetId), 15);
        $this->fleetRepository->save($fleet->getState());

        self::expectException(NumberBoatsCantBeNegative::class);
        $this->updateFleet->execute($fleetId, $newTotal = -1, $title = 'hobie cat', Fleet::STATE_INACTIVE);
    }

    /**
     * @test
     */
    public function shouldNotUpdateFleetWhenItDoesNotExist()
    {
        $fleetId = 'abc';

        self::expectException(FleetNotFound::class);
        $this->updateFleet->execute($fleetId, $newTotal = 20, $title = 'hobie cat', Fleet::STATE_INACTIVE);
    }

    /**
     * @test
     */
    public function shouldNotEditFleetWithSameName()
    {
        $fleet = new FleetState('abc', 10, Fleet::STATE_ACTIVE, ['name' => ['fr' => 'hobie cat 15']]);
        $this->fleetRepository->save($fleet);

        $fleet = new FleetState('abcd', 10, Fleet::STATE_ACTIVE, ['name' => ['fr' => 'hobie cat']]);
        $this->fleetRepository->save($fleet);

        self::expectException(FleetAlreadyExist::class);
        app(UpdateFleet::class)->execute('abcd', 20, 'hobie cat 15', Fleet::STATE_INACTIVE);
    }
}
