<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\DisableFleet;
use App\Signing\Signing\Domain\UseCases\EnableFleet;
use Tests\TestCase;

class EnableFleetTest extends TestCase
{
    private EnableFleet $enableFleet;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFleet = app(EnableFleet::class);
    }

    /**
     * @test
     */
    public function shouldEnableFleet()
    {
        $fleetId = 'abcd';
        $fleet = new Fleet(new Id($fleetId), 20, Fleet::STATE_INACTIVE);
        $this->fleetRepository->save($fleet->getState());

        $this->enableFleet->execute($fleetId);

        $fleetExpected = new Fleet(new Id($fleetId), 20, Fleet::STATE_ACTIVE);
        $fleetSaved = $this->fleetRepository->get($fleetId);
        self::assertEquals($fleetExpected, $fleetSaved, 'Fleet has not been enabled');
    }
}
