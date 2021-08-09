<?php


namespace Tests\Feature\Query\RentalPackages;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Application\ViewModel\RentalPackageViewModel;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
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

        $rentalPackage = new RentalPackage('abc', new FleetCollection([$supportId1]), 'name', 10);
        $this->rentalPackageRepository->save($rentalPackage->getState());

        $rentalPackages = app(GetRentalPackages::class)->execute();
        $rentalPackagesExpected = [new RentalPackageViewModel('abc', 'name', [$hobieCatName], 10)];
        self::assertEquals($rentalPackagesExpected, $rentalPackages);
    }
}
