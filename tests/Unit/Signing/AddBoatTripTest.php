<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AddBoatTripTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddABoatTrip()
    {
        $support = new Support(new Id($supportId = Uuid::uuid4()), 20);
        $this->supportRepository->save($support);
        $this->identityProvider->add($id = 'abc');
        app(AddBoatTrip::class)->execute($supportId, $qty = 2, $name = "tabarly", $numberHours = 3);

        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        $boatTripExpected = new BoatTrip(new Id('abc'), $boatTripDuration, $supportId, $qty, $name);

        $boatTripSaved = $this->boatTripRepository->get($id);
        self::assertEquals($boatTripExpected, $boatTripSaved);
    }

    /**
     * @test
     */
    public function shouldNotAddBoatTripWhenSupportNotAvailable()
    {
        $support = new Support(new Id($supportId = Uuid::uuid4()), 5);
        $this->supportRepository->save($support);

        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), 2);
        $boatTrip = new BoatTrip(new Id(), $boatTripDuration, $supportId, 5, $name = "tabarly");
        $this->boatTripRepository->add($boatTrip);

        self::expectException(\Exception::class);
        self::expectExceptionMessage('support_not_available');
        app(AddBoatTrip::class)->execute($supportId, $qty = 3, $name, $numberHours = 3);
    }
}
