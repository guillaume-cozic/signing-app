<?php


namespace Database\Seeders;


use App\Models\User;
use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;

class DemoSeeder extends Seeder
{
    use WithFaker;

    public function run()
    {
        $this->setUpFaker();

        $user = new User();
        $user->email = 'admin@wellsail.io';
        $user->firstname = 'admin';
        $user->uuid = Uuid::uuid4();
        $user->surname = 'admin';
        $user->password = bcrypt('secret');
        $user->save();

        $teamModel = config('teamwork.team_model');

        $sailingClub = $teamModel::create([
            'name' => 'Club nautique de démo',
            'owner_id' => $user->id,
        ]);
        $user->attachTeam($sailingClub);

        $user->assignRole('demo');
        app(ContextService::class)->set($sailingClub->id);
        $fleets = [
              'Kayak' => 10,
              'Paddle' => 30,
              'Hobie cat 15' => 5,
              'Hobie cat T1' => 10,
              'Fusion' => 5,
              'Pav débutant' => 8,
              'Pav performance' => 10,
              'Wing foil' => 5,
              'Optimist' => 30,
        ];
        $ids = [];
        $idsByNumber = [];
        foreach($fleets as $fleet => $fleetQty){
            (new Fleet($id = new Id(), $fleetQty))->create($fleet);
            $ids[$fleet] = $id->id();
            $idsByNumber[] = $id->id();
        }

        $rentalPackages = [
            'Forfait Kayak/Paddle' => [
                'fleets' => ['Kayak', 'Paddle']
            ],
            'Forfait Hobie cat 15' => [
                'fleets' => ['Hobie cat 15']
            ],
            'Forfait Fusion' => [
                'fleets' => ['Fusion']
            ],
        ];

        $idsRental = [];
        foreach ($rentalPackages as $rentalPackageName => $rentalPackage){
            $fleets = [];
            foreach($rentalPackage['fleets'] as $fleet){
                $fleets[] = $ids[$fleet];
            }
            $rt = new RentalPackage($id = Uuid::uuid4(), new FleetCollection($fleets), $rentalPackageName, 730);
            $rt->save();
            $idsRental[$rentalPackageName] = $rt;
        }

        $sailor = new Sailor(name:'John Doe', sailorId: $sailorIdJohn = Uuid::uuid4());
        $sailor->create();
        $sailor->addRentalPackage(Uuid::uuid4(), $idsRental['Forfait Kayak/Paddle'], 10, $idsRental['Forfait Kayak/Paddle']->validityEndAtFromNow());
        $sailor->addRentalPackage(Uuid::uuid4(), $idsRental['Forfait Fusion'], 6, $idsRental['Forfait Fusion']->validityEndAtFromNow());

        $sailor = new Sailor(name:'Lisa marchand', sailorId: $sailorId = Uuid::uuid4());
        $sailor->create();
        $sailorRentalPackage = $sailor->addRentalPackage(Uuid::uuid4(), $idsRental['Forfait Hobie cat 15'], 3, $idsRental['Forfait Hobie cat 15']->validityEndAtFromNow());

        $sailorRentalPackage->decreaseHours(2);
        $sailorRentalPackage->decreaseHours(1);

        $boatTrip = BoatTripBuilder::build(Uuid::uuid4())
            ->withSailor(sailorId: $sailorIdJohn, name:'John Doe')
            ->withBoats([$ids['Kayak'] => 2])
            ->inProgress(1);
        app(BoatTripRepository::class)->save($boatTrip->getState());

        $boatTrip = BoatTripBuilder::build(Uuid::uuid4())
            ->withSailor(sailorId: $sailorIdJohn, name:'John Doe')
            ->withBoats([$ids['Hobie cat 15'] => 2])
            ->inProgress(1);
        app(BoatTripRepository::class)->save($boatTrip->getState());


        for($i = 0; $i < 50; $i++){

            $boats = [];
            $boatsNumber = rand(1, 2);
            for($j=0; $j<$boatsNumber; $j++){
                $boats[$idsByNumber[rand(0, 8)]] = rand(1, 15);
            }

            $days = rand(0, 30);
            $startAt = (new \DateTime('-'.$days.' days'))->setTime(rand(9, 19), 0);
            $boatTrip = BoatTripBuilder::build(Uuid::uuid4())
                ->withBoats($boats)
                ->withSailor(name:$this->faker->firstName)
                ->fromState(rand(1, 3), $startAt, $startAt);
            app(BoatTripRepository::class)->save($boatTrip->getState());

        }
    }
}
