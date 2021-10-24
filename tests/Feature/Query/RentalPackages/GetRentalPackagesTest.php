<?php


namespace Tests\Feature\Query\RentalPackages;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Application\ViewModel\RentalPackageViewModel;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GetRentalPackagesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldGetEmptyList()
    {
        self::assertEmpty(app(GetRentalPackages::class)->execute());
    }

    /**
     * @test
     */
    public function shouldGetRentalPackage()
    {
        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()->toString()), 20);
        $this->fleetRepository->save($support1->getState());

        $support1Model = FleetModel::where('uuid', $supportId1)->first();
        $support1Model->name = $hobieCatName = 'Hobie cat 15';
        $support1Model->save();

        $sailor = new Sailor(name:'frank', sailorId:'sailor_frank_id');
        $this->sailorRepository->save($sailor->getState());

        $sailor = new Sailor(name:'frank', sailorId:'sailor_guillaume_id');
        $this->sailorRepository->save($sailor->getState());

        $rentalPackage = new RentalPackage('abc', new FleetCollection([$supportId1]), 'name', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailorRentalPackage = new SailorRentalPackage('abcde', 'sailor_frank_id', 'abc', new Carbon(), 10);
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        $sailorRentalPackage = new SailorRentalPackage('abcd', 'sailor_guillaume_id', 'abc', new Carbon(), 0);
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        $rentalPackages = app(GetRentalPackages::class)->execute();
        $rentalPackagesExpected = [new RentalPackageViewModel('abc', 'name', [$hobieCatName], 10, 1)];
        self::assertEquals($rentalPackagesExpected, $rentalPackages);
    }
}
