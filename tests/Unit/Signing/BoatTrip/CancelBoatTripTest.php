<?php


namespace Tests\Unit\Signing\BoatTrip;


use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Exceptions\BoatTripAlreadyEnded;
use App\Signing\Signing\Domain\UseCases\BoatTrip\CancelBoatTrip;
use Tests\TestCase;

class CancelBoatTripTest extends TestCase
{
    private CancelBoatTrip $cancelBoatTrip;

    public function setUp(): void
    {
        parent::setUp();
        $this->cancelBoatTrip = app(CancelBoatTrip::class);
    }

    /**
     * @test
     */
    public function shouldCancelBoatTrip()
    {
        $boatTrip = BoatTripBuilder::build('abcde')->withSailor('Tabarly')->inProgress(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $this->cancelBoatTrip->execute('abcde');

        self::assertNull($this->boatTripRepository->get('abcde'));
    }

    /**
     * @test
     */
    public function shouldNotCancelEndedBoatTrip()
    {
        $boatTrip = BoatTripBuilder::build('abcde')->withSailor('Tabarly')->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        self::expectException(BoatTripAlreadyEnded::class);
        $this->cancelBoatTrip->execute('abcde');
    }
}
