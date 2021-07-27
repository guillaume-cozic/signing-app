<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Exceptions\RentalPackageValidityNegative;
use App\Signing\Signing\Domain\Exceptions\RentalPackageWithoutFleet;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateRentalPackage;

class CreateRentalPackageImpl implements CreateRentalPackage
{
    private array $fleets;

    public function __construct(
        private FleetRepository $fleetRepository,
        private RentalPackageRepository $rentalPackageRepository
    ){}

    public function execute(string $rentalPackageId, array $fleets, int $validityInDays = null)
    {
        $this->CheckIfFleetNotEmpty($fleets);
        $this->checkIfFleetsExist($fleets);

        if(isset($validityInDays) && $validityInDays <= 0){
            throw new RentalPackageValidityNegative();
        }
        $this->rentalPackageRepository->save(new RentalPackage($rentalPackageId, $fleets));
    }

    /**
     * @param array $fleets
     * @throws FleetNotFound
     */
    private function checkIfFleetsExist(array $fleets): void
    {
        foreach ($fleets as $fleetId) {
            $fleet = $this->fleetRepository->get($fleetId);
            if (!isset($fleet)) {
                throw new FleetNotFound();
            }
            $this->fleets[] = $fleet;
        }
    }

    /**
     * @param array $fleets
     * @throws RentalPackageWithoutFleet
     */
    private function CheckIfFleetNotEmpty(array $fleets): void
    {
        if (empty($fleets)) {
            throw new RentalPackageWithoutFleet();
        }
    }
}
