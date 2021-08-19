<?php

namespace App\Exports;

use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SailorRentalPackageTemplateImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $rentalPackages = app(ReadRentalPackageRepository::class)->all();
        foreach($rentalPackages as $rentalPackage){
            $sheets[] = new SailorRentalPackageSheet($rentalPackage->name(), $rentalPackage->id());
        }
        return $sheets ?? [];
    }
}
