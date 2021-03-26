<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\BoatTripModel;

class SqlReadBoatTripRepository implements ReadBoatTripRepository
{
    public function __construct(private ContextService $contextService){}

    public function getInProgress(int $page = 1, int $perPage = 10)
    {
        return BoatTripModel::query()
            ->whereNull('end_at')
            ->where('sailing_club_id', $this->contextService->get()->sailingClubId())
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function (BoatTripModel $item) {
                return $item->toDto();
            });
    }

}
