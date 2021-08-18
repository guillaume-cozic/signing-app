<?php

namespace App\Imports;

use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Ramsey\Uuid\Uuid;

class SailorRentalPackageImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $createSailorRentalPackage = app(CreateSailorRentalPackage::class);
        $ignoreNextLine = false;
        foreach ($collection as $rows){
            $keyName = 0;
            $keyHour = 1;
            $keyValidity = 2;

            if(strpos($rows[0], 'Auto : ') !== false){
                if($ignoreNextLine === false) {
                    $rentalPackageId = $rows[2];
                    $ignoreNextLine = true;
                }
                continue;
            }else{
                $ignoreNextLine = false;
            }
            $createSailorRentalPackage->execute(Uuid::uuid4(), $rentalPackageId, $rows[$keyName], $rows[$keyHour]);
        }
    }


}
