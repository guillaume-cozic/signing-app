<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use App\Signing\Signing\Domain\UseCases\AddMemberBoatTrip;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AddMemberBoatTripTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddMemberBoatTrip()
    {
        $supportId = Uuid::uuid4();
        $memberId = Uuid::uuid4();
        $this->identityProvider->add($boatTripId = 'abc');

        $support = new Support(new Id($supportId), 20);
        $this->supportRepository->save($support);

        app(AddMemberBoatTrip::class)->execute($memberId, $supportId, $numberBoats = 1, $numberHours = 3);

        $boatTripSaved = $this->boatTripRepository->get($boatTripId);

        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), $numberHours);
        $boatTripExpected = new BoatTrip(id:new Id($boatTripId), boatTripDuration: $boatTripDuration, supportId: $supportId, qty: 1,  memberId: $memberId);
        self::assertEquals($boatTripExpected, $boatTripSaved);
    }

    /**
     * @test
     */
    public function shouldNotAddMemberBoatTripWhenSupportNotAvailable()
    {
        $supportId = Uuid::uuid4();
        $memberId = Uuid::uuid4();

        $support = new Support(new Id($supportId), 5);
        $this->supportRepository->save($support);

        $boatTripDuration = new BoatTripDuration($this->dateProvider->current(), 2);
        $boatTrip = new BoatTrip(new Id(), $boatTripDuration, $supportId, 5, $name = "tabarly");
        $this->boatTripRepository->add($boatTrip);

        self::expectException(\Exception::class);
        self::expectExceptionMessage('support_not_available');
        app(AddMemberBoatTrip::class)->execute($memberId, $supportId, $numberBoats = 1, $numberHours = 3);
    }

    /**
     * @test
     */
    public function shouldAddMemberBoatTripWithOwnerSupport()
    {
        $memberId = Uuid::uuid4();
        $this->identityProvider->add($boatTripId = 'abc');

        app(AddMemberBoatTrip::class)->execute($memberId);

        $boatTripSaved = $this->boatTripRepository->get($boatTripId);
        $boatTripDuration = new BoatTripDuration($this->dateProvider->current());
        $boatTripExpected = new BoatTrip(id:new Id($boatTripId), boatTripDuration:$boatTripDuration, memberId: $memberId);

        self::assertEquals($boatTripExpected, $boatTripSaved);
    }
}
