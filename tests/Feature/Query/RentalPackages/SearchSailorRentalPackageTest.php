<?php


namespace Tests\Feature\Query\RentalPackages;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Application\ViewModel\SailorRentalPackageViewModel;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\SearchSailorRentalPackages;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class SearchSailorRentalPackageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldGetEmptyList()
    {
        self::assertEmpty(app(SearchSailorRentalPackages::class)->execute());
    }


    /**
     * @test
     */
    public function shouldGetSailorRentalPackage()
    {
        $support1 = new Fleet(new Id($supportId1 = Uuid::uuid4()->toString()), 20);
        $this->fleetRepository->save($support1->getState());

        $support1Model = FleetModel::where('uuid', $supportId1)->first();
        $support1Model->name = $hobieCatName = 'Hobie cat 15';
        $support1Model->save();

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection([$supportId1]), 'rental_package_name', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $sailor = new Sailor(name:'frank', sailorId:'sailor_id');
        $this->sailorRepository->save($sailor->getState());

        $sailorRentalPackage = new SailorRentalPackage('abc', 'sailor_id', 'rental_package_id', new Carbon(), 10);
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        $sailorViewModel = new SailorRentalPackageViewModel('abc', 'frank', 'rental_package_name', 10);
        $sailorRentalPackagesList = app(SearchSailorRentalPackages::class)->execute();

        self::assertEquals($sailorViewModel, $sailorRentalPackagesList[0]);
    }
}
