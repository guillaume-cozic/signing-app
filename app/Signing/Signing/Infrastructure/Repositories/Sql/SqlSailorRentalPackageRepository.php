<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql;


use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
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
        $sailorRentalPackageModel = SailorRentalPackageModel::query()->where('uuid', $id)->first();
        if(!isset($sailorRentalPackageModel)){
            return null;
        }
        $sailor = SailorModel::query()->where('id', $sailorRentalPackageModel->sailor_id)->first();
        return new SailorRentalPackage(
            $sailorRentalPackageModel->uuid,
            $sailor->name,
            $sailorRentalPackageModel->rentalPackage->uuid,
            $sailorRentalPackageModel->end_validity,
            $sailorRentalPackageModel->hours,
        );
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
        $sailorRentalPackageModel = SailorRentalPackageModel::query()
            ->where('rental_package_id', $rentalPackage->id)
            ->where('sailor_id', $sailor->id)
            ->first();
        if(!isset($sailorRentalPackageModel)){
            return null;
        }
        $sailor = SailorModel::query()->where('id', $sailorRentalPackageModel->sailor_id)->first();
        return new SailorRentalPackage(
            $sailorRentalPackageModel->uuid,
            $sailor->name,
            $sailorRentalPackageModel->rentalPackage->uuid,
            $sailorRentalPackageModel->end_validity,
            $sailorRentalPackageModel->hours,
        );
    }

    public function save(SailorRentalPackageState $sailorRentalPackageState)
    {
        $rentalPackage = RentalPackageModel::query()->where('uuid', $sailorRentalPackageState->rentalPackageId())->first();
        $sailor = SailorModel::query()->where('name', $sailorRentalPackageState->name())->first();
        if(!isset($sailor)){
            $sailor = new SailorModel();
            $sailor->name = $sailorRentalPackageState->name();
            $sailor->uuid = Uuid::uuid4();
            $sailor->save();
        }

        $sailorRentalPackageModel = SailorRentalPackageModel::query()
                ->where('uuid', $sailorRentalPackageState->id())->first() ?? new SailorRentalPackageModel();
        $sailorRentalPackageModel->uuid = $sailorRentalPackageState->id();
        $sailorRentalPackageModel->rental_package_id = $rentalPackage?->id;
        $sailorRentalPackageModel->hours = $sailorRentalPackageState->hours();
        $sailorRentalPackageModel->end_validity = $sailorRentalPackageState->endValidity();
        $sailorRentalPackageModel->sailing_club_id = $this->contextService->get()->sailingClubId();
        $sailorRentalPackageModel->sailor_id = $sailor->id;
        $sailorRentalPackageModel->save();
    }
}
