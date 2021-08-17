<?php


namespace Tests\Feature\Query\RentalPackages;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Application\ViewModel\ActionsSailorRentalPackageViewModel;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\ActionSailor;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Exceptions\SailorRentalPackageNotFound;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetActionsSailorRentalPackage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetActionsSailorRentalPackageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function shouldGetEmptyList()
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection(['fleet']), 'forfait', 365);
        $this->rentalPackageRepository->save($rentalPackage->getState());
        $sailorRentalPackage = new SailorRentalPackage(
            'sailor_rental_package_id',
                'tabarly',
            'rental_package_id',
            Carbon::instance($this->dateProvider->current()),
            10,
            []
        );
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        $actions = app(GetActionsSailorRentalPackage::class)->execute('sailor_rental_package_id');
        self::assertEmpty($actions);
    }

    /**
     * @test
     */
    public function shouldNotGetActionsWhenSailorRentalPackageDoesNotExist()
    {
        self::expectException(SailorRentalPackageNotFound::class);
        app(GetActionsSailorRentalPackage::class)->execute('sailor_rental_package_id');
    }

    /**
     * @test
     */
    public function shouldGetActionsList()
    {
        $fleet = new Fleet(new Id('fleet'), 10);
        $this->fleetRepository->save($fleet->getState());

        $rentalPackage = new RentalPackage('rental_package_id', new FleetCollection(['fleet']), 'forfait', 365);
        $this->rentalPackageRepository->save($rentalPackage->getState());
        $sailorRentalPackage = new SailorRentalPackage(
            'sailor_rental_package_id',
            'tabarly',
            'rental_package_id',
            Carbon::instance($this->dateProvider->current()),
            10,
            [new ActionSailor(ActionSailor::ADD_HOURS, 1, Carbon::instance($this->dateProvider->current()))]
        );
        $this->sailorRentalPackageRepository->save($sailorRentalPackage->getState());

        $actions = app(GetActionsSailorRentalPackage::class)->execute('sailor_rental_package_id');

        $expected = new ActionsSailorRentalPackageViewModel(ActionSailor::ADD_HOURS, 1, Carbon::createFromFormat('Y-m-d H:i', $this->dateProvider->current()->format('Y-m-d H:i')));
        self::assertEquals($actions[0], $expected);
    }
}
