<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\Entities\Sailor;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\RentalPackageModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorRentalPackageModel;
use Ramsey\Uuid\Uuid;

class SqlSailorRentalPackageRepository implements SailorRentalPackageRepository
{
    public function __construct(private ContextService $contextService){}

    public function get(string $id): ?SailorRentalPackage
    {
        return SailorRentalPackageModel::query()
            ->where('uuid', $id)->first()
            ?->toDomain();
    }

    public function getByNameAndRentalPackage(string $name, string $packageRentalId): ?SailorRentalPackage
    {
        $rentalPackage = RentalPackageModel::query()->where('uuid', $packageRentalId)->first();
        if(!isset($rentalPackage)){
            return null;
        }
        $sailor = SailorModel::query()->where('name', $name)->first();
        if(!isset($sailor)){
            return null;
        }
        return SailorRentalPackageModel::query()
            ->where('rental_package_id', $rentalPackage->id)
            ->where('sailor_id', $sailor->id)
            ->first()
            ?->toDomain();
    }

    public function save(SailorRentalPackageState $sailorRentalPackageState)
    {
        $rentalPackage = RentalPackageModel::query()->where('uuid', $sailorRentalPackageState->rentalPackageId())->first();
        $sailor = SailorModel::query()->where('uuid', $sailorRentalPackageState->sailorId())->first();

        $sailorRentalPackageModel = SailorRentalPackageModel::query()
                ->where('uuid', $sailorRentalPackageState->id())->first() ?? new SailorRentalPackageModel();
        $sailorRentalPackageModel->uuid = $sailorRentalPackageState->id();
        $sailorRentalPackageModel->rental_package_id = $rentalPackage?->id;
        $sailorRentalPackageModel->hours = $sailorRentalPackageState->hours();
        $sailorRentalPackageModel->end_validity = $sailorRentalPackageState->endValidity();
        $sailorRentalPackageModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $sailorRentalPackageModel->sailor_id = $sailor->id;
        $sailorRentalPackageModel->actions = $sailorRentalPackageState->actions();
        $sailorRentalPackageModel->save();
    }

    public function getBySailorAndRentalPackage(Sailor $sailor, RentalPackage $rentalPackage): ?SailorRentalPackage
    {
        return SailorRentalPackageModel::query()
            ->where('rental_package_id', $rentalPackage->surrogateId())
            ->where('sailor_id', $sailor->surrogateId())
            ->first()
            ?->toDomain();
    }


}
