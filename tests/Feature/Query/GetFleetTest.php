<?php


namespace Tests\Feature\Query;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Dto\FleetDto;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\UseCases\Query\GetFleet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetFleetTest extends TestCase
{
    use DatabaseTransactions;

    private GetFleet $getFleet;

    public function setUp(): void
    {
        parent::setUp();
        $this->getFleet = app(GetFleet::class);
    }

    /**
     * @test
     */
    public function shouldNotGetFleetWhenIsDoesNotExist()
    {
        $fleetsId = 'abc';

        self::expectException(FleetNotFound::class);
        $this->getFleet->execute($fleetsId);
    }

    /**
     * @test
     */
    public function shouldGetFleet()
    {
        $fleetsId = 'abc';
        $fleet = new Fleet(new Id($fleetsId), 20, Fleet::STATE_INACTIVE);
        $fleet->create('hobie cat', '');

        $fleetDtoExpected = new FleetDto($fleetsId, 'hobie cat', 20, Fleet::STATE_INACTIVE);

        $fleetDto = $this->getFleet->execute($fleetsId);
        self::assertEquals($fleetDtoExpected, $fleetDto);
    }
}
