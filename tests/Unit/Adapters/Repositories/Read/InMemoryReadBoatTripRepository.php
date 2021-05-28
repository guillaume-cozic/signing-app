<?php


namespace Tests\Unit\Adapters\Repositories\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;

class InMemoryReadBoatTripRepository implements ReadBoatTripRepository
{
    public function __construct(
        private array $boatTrips = []
    ){}

    public function getInProgress(?string $search = '', int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = [])
    {
        return [];
    }

    public function save($boatTrip)
    {
        $this->boatTrips[] = $boatTrip;
    }

    public function getNearToFinishOrStart():array
    {
        return $this->boatTrips;
    }

}
