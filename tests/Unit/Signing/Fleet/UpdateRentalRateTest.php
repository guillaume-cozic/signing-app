<?php


namespace Tests\Unit\Signing\Fleet;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\FleetState;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\UseCases\Fleet\UpdateFleetRentalRate;
use Tests\TestCase;

class UpdateRentalRateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function shouldUpdateRentalRate()
    {
        $fleet = new Fleet(new Id('abc'), 20);
        $this->fleetRepository->save($fleet->getState());

        $rents = [
            'hours' => [1 => 20, 5 => 50, 10 => 250]
        ];
        app(UpdateFleetRentalRate::class)->execute('abc', $rents);

        $fleetStateExpected = new FleetState('abc', 20, Fleet::STATE_ACTIVE, $rents);
        $fleetSaved = $this->fleetRepository->get('abc');
        self::assertEquals($fleetStateExpected, $fleetSaved->getState());
    }

    /**
     * @test
     */
    public function shouldNotUpdateRentalRateWhenFleetDoesNotExist()
    {
        $rents = [
            'hours' => [1 => 20, 5 => 50, 10 => 250]
        ];
        self::expectException(FleetNotFound::class);
        app(UpdateFleetRentalRate::class)->execute('abc', $rents);
    }
}
