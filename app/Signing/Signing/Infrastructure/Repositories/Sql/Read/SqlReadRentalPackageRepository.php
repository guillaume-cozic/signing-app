<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Read;


use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\RentalPackageModel;

class SqlReadRentalPackageRepository implements ReadRentalPackageRepository
{
    public function all():array
    {
        return RentalPackageModel::all()
            ->transform(function (RentalPackageModel $rentalPackage){
                return $rentalPackage->toState();
            })
            ?->toArray();
    }
}
