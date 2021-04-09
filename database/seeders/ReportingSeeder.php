<?php


namespace Database\Seeders;


use App\Models\User;
use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;

class ReportingSeeder extends Seeder
{
    use WithFaker;

    public function run()
    {
        app(ContextService::class)->set(1);
        $this->setUpFaker();
        \App\Models\User::factory(10)->create();

        for($i = 0; $i < 10; $i++) {
            $fleet = new Fleet(new Id(Uuid::uuid4()), rand(1, 10));
            $fleet->create($this->faker->company, '');
        }

        $users = User::all();
        $fleets = FleetModel::all();
        for($i = 0; $i < 50; $i++){

            $boats = [];
            $boatsNumber = rand(0, 2);
            for($j=0; $j<$boatsNumber; $j++){
                $boats[$fleets->random(1)->first()->uuid] = rand(1, 15);
            }

            $days = rand(0, 30);
            $startAt = (new \DateTime('-'.$days.' days'));
            $boatTrip = BoatTripBuilder::build(Uuid::uuid4())
                ->withBoats($boats)
                ->withSailor($users->random(1)->first()->uuid)
                ->fromState(rand(1, 3), $startAt, $startAt);
            app(BoatTripRepository::class)->save($boatTrip->getState());


            $days = rand(0, 30) + 365;
            $startAt = (new \DateTime('-'.$days.' days'));
            $boatTrip = BoatTripBuilder::build(Uuid::uuid4())
                ->withBoats($boats)
                ->withSailor($users->random(1)->first()->uuid)
                ->fromState(rand(1, 3), $startAt, $startAt);

            app(BoatTripRepository::class)->save($boatTrip->getState());
        }
    }
}
