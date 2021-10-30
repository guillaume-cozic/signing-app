<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use App\Signing\Signing\Domain\Repositories\Read\ReadSailorRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\RentalPackageModel;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorRentalPackageModel;

class SqlReadSailorRentalPackageRepository implements ReadSailorRentalPackageRepository
{
    public function search(?string $search = '', int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = [])
    {
        return SailorRentalPackageModel::query()
            ->selectRaw('*, sailor_rental_package.uuid as sailor_rental_id')
            ->join('sailor', 'sailor.id', 'sailor_rental_package.sailor_id')
            ->join('rental_package', 'rental_package.id', 'sailor_rental_package.rental_package_id')
            ->when($search !== null, function($query) use ($search){
                $query->where('sailor.name', 'LIKE', '%'.$search.'%')
                      ->orWhere('rental_package.name', 'LIKE', '%'.$search.'%');
            })
            ->when(isset($filters['rental_package_id']) && $filters['rental_package_id'] !== null, function($query) use ($filters) {
                $rentalPackageModel = RentalPackageModel::query()->where('uuid', $filters['rental_package_id'])->first();
                $query->where('rental_package.id', $rentalPackageModel->id);
            })
            ->when(isset($sort), function ($query) use($sort, $dirSort){
                switch ($sort){
                    case 'sailor_name':
                        return $query->orderBy('sailor.name', $dirSort);
                    case 'rental_name':
                        return $query->orderBy('rental_package.name', $dirSort);
                    case 'hours':
                        return $query->orderBy('hours', $dirSort);
                }
            })
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function (SailorRentalPackageModel $item) {
                return $item->toState();
            })
        ;
    }

    public function getNumberAvailable(string $rentalPackageId): int
    {
        $rentalPackage = RentalPackageModel::query()
            ->where('uuid', $rentalPackageId)
            ->first();
        return SailorRentalPackageModel::query()
            ->where('hours', '>', 0)
            ->where('rental_package_id', $rentalPackage->id)
            ->count();
    }

    public function get(string $sailorRentalPackageId):? SailorRentalPackageState
    {
        return SailorRentalPackageModel::query()
            ->where('uuid', $sailorRentalPackageId)
            ->first()
            ?->toState()
        ;
    }


}
