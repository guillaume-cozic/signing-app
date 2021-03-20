<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\Vo;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;
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
        $supportId = Uuid::uuid4()->toString();
        $memberId = Uuid::uuid4()->toString();
        $this->identityProvider->add($boatTripId = 'abc');

        $support = new Fleet(new Id($supportId), 20);
        $this->fleetRepository->save($support);

        app(AddMemberBoatTrip::class)->execute($memberId, [$supportId => $numberBoats = 1], $numberHours = 3);

        $boatTripSaved = $this->boatTripRepository->get($boatTripId);

        $boatTripExpected = BoatTripBuilder::build($boatTripId)
            ->withBoats([$supportId => 1])
            ->withSailor(memberId:$memberId)
            ->inProgress(numberHours:$numberHours);

        self::assertEquals($boatTripExpected, $boatTripSaved);
    }

    /**
     * @test
     */
    public function shouldNotAddMemberBoatTripWhenSupportNotAvailable()
    {
        $supportId = Uuid::uuid4()->toString();
        $memberId = Uuid::uuid4()->toString();

        $support = new Fleet(new Id($supportId), 5);
        $this->fleetRepository->save($support);


        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats([$supportId => 5])
            ->withSailor(name:'Tabarly')
            ->inProgress(numberHours:2);
        $this->boatTripRepository->add($boatTrip);

        self::expectException(BoatNotAvailable::class);
        self::expectExceptionMessage('support_not_available');
        app(AddMemberBoatTrip::class)->execute($memberId, [$supportId => 1], $numberHours = 3);
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

        $boatTripExpected = BoatTripBuilder::build($boatTripId)
            ->withSailor(memberId:$memberId)
            ->inProgress(numberHours:null);

        self::assertEquals($boatTripExpected, $boatTripSaved);
    }
}
