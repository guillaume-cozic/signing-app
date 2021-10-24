<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\UseCases\DisableFleet;
use Tests\TestCase;

class DisableFleetTest extends TestCase
{
    private DisableFleet $disableFleet;

    public function setUp(): void
    {
        parent::setUp();
        $this->disableFleet = app(DisableFleet::class);
    }

    /**
     * @test
     */
    public function shouldDisableFleet()
    {
        $fleetId = 'abcd';
        $fleet = new Fleet(new Id($fleetId), 20, Fleet::STATE_ACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $this->disableFleet->execute($fleetId);

        $fleetExpected = new Fleet(new Id($fleetId), 20, Fleet::STATE_INACTIVE);
        $fleetSaved = $this->fleetRepository->get($fleetId);
        self::assertEquals($fleetExpected, $fleetSaved, 'Fleet has not been disabled');
    }
}
