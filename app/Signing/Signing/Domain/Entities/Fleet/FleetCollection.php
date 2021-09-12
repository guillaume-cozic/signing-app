<?php


namespace App\Signing\Signing\Domain\Entities\Fleet;


use App\Signing\Signing\Domain\Exceptions\FleetNotFound;
use App\Signing\Signing\Domain\Repositories\FleetRepository;

class FleetCollection
{
    private FleetRepository $fleetRepository;

    /**
     * @throws FleetNotFound
     */
    public function __construct(
        private array $fleets
    ){
        $this->fleetRepository = app(FleetRepository::class);
        $this->checkIfFleetsExist();
    }

    public function toArray():array
    {
        return $this->fleets;
    }

    /**
     * @param array $fleets
     * @throws FleetNotFound
     */
    private function checkIfFleetsExist(): void
    {
        foreach ($this->fleets as $fleetId) {
            $fleet = $this->fleetRepository->get($fleetId);
            if (!isset($fleet)) {
                throw new FleetNotFound();
            }
        }
    }
}
