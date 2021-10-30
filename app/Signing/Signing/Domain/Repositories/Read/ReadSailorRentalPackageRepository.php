<?php


namespace App\Signing\Signing\Domain\Repositories\Read;


use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;

interface ReadSailorRentalPackageRepository
{
    public function search(?string $search = '', int $page = 1, int $perPage = 10, string $sort = null, string $dirSort = "asc", array $filters = []);
    public function getNumberAvailable(string $rentalPackageId):int;
    public function get(string $sailorRentalPackageId):?SailorRentalPackageState;
}
