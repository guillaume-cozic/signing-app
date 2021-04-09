<?php


namespace Tests\Feature\Query;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\Query\GetNumberBoatsOfFleetAvailable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetNumberBoatsOfFleetAvailableTest extends TestCase
{
    use DatabaseTransactions;

    private GetNumberBoatsOfFleetAvailable $getNumberBoatsOfFleetAvailable;

    public function setUp(): void
    {
        parent::setUp();
        $this->getNumberBoatsOfFleetAvailable = app(GetNumberBoatsOfFleetAvailable::class);
    }

    /**
     * @test
     */
    public function shouldGetEmptyList()
    {
        $list = $this->getNumberBoatsOfFleetAvailable->execute();
        self::assertEquals([], $list);
    }

    /**
     * @test
     */
    public function shouldGetList()
    {
        $fleet = new Fleet(new Id('abc'), 20);
        $fleet->create('Hobie cat', '');

        $list = $this->getNumberBoatsOfFleetAvailable->execute();
        $expected = [
            'id' => 'abc',
            'name' => 'Hobie cat',
            'available' => 20,
            'total' => 20,
        ];
        self::assertEquals([$expected], $list);
    }

    /**
     * @test
     */
    public function shouldOnlyGetActiveFleet()
    {
        $fleet = new Fleet(new Id('abc'), 20, Fleet::STATE_INACTIVE);
        $fleet->create('Hobie cat', '');

        $list = $this->getNumberBoatsOfFleetAvailable->execute();
        self::assertEquals([], $list);
    }

    /**
     * @test
     */
    public function shouldGetTotalAvailableWhenBoatTripActive()
    {
        $fleet = new Fleet(new Id('abc'), 20);
        $fleet->create('Hobie cat', '');

        $boatTrip = BoatTripBuilder::build('abc')
            ->withSailor('Tabarly')
            ->withBoats(['abc' => 9])
            ->inProgress(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $boatTrip = BoatTripBuilder::build('abcd')
            ->withSailor('Tabarly')
            ->withBoats(['abc' => 9])
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $list = $this->getNumberBoatsOfFleetAvailable->execute();
        $expected = [
            'id' => 'abc',
            'name' => 'Hobie cat',
            'available' => 11,
            'total' => 20
        ];
        self::assertEquals([$expected], $list);
    }
}
