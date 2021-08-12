<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\Fleet\FleetCollection;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackageState;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\RentalPackageModel;

class SqlRentalPackageRepository implements RentalPackageRepository
{
    public function __construct(private ContextService $contextService){}

    public function get(string $id): ?RentalPackage
    {
        $rentalPackageModel = RentalPackageModel::query()->where('uuid', $id)->first();
        if(!isset($rentalPackageModel)){
            return null;
        }
        return new RentalPackage(
            $id,
            new FleetCollection($rentalPackageModel->fleets),
            $rentalPackageModel->name,
            $rentalPackageModel->validity
        );
    }

    public function save(RentalPackageState $r)
    {
        $rentalPackageModel = RentalPackageModel::query()->where('uuid', $r->id())->first() ?? new RentalPackageModel();
        $rentalPackageModel->uuid = $r->id();
        $rentalPackageModel->fleets = $r->fleets();
        $rentalPackageModel->validity = $r->validity();
        $rentalPackageModel->name = $r->name();
        $rentalPackageModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $rentalPackageModel->save();
    }

    public function getByFleet(string $fleetId): ?RentalPackage
    {
        $rentalPackageModel = RentalPackageModel::query()
            ->whereJsonContains('fleets', $fleetId)
            ->first();
        if(!isset($rentalPackageModel)){
            return null;
        }
        return new RentalPackage(
            $rentalPackageModel->uuid,
            new FleetCollection($rentalPackageModel->fleets),
            $rentalPackageModel->name,
            $rentalPackageModel->validity
        );
    }
}
