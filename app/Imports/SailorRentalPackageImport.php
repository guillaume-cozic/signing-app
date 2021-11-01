<?php

namespace App\Imports;

use App\Signing\Shared\Exception\DomainException;
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
        $err = 0;
        $processed = 0;
        $errors = [];
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

            if(!isset($rows[$keyName]) || trim($rows[$keyName]) == ''){
                $err++;
                $errors[] = $rows;
                continue;
            }
            if(!isset($rows[$keyHour]) || trim($rows[$keyHour]) == '' || !is_numeric($rows[$keyHour]) || $rows[$keyHour] < 0){
                $err++;
                $errors[] = $rows;
                continue;
            }
            try {
                $createSailorRentalPackage->execute(Uuid::uuid4(), $rentalPackageId, $rows[$keyName], $rows[$keyHour]);
                $processed++;
            }catch (DomainException $e){
                $err++;
                $errors[] = $rows;
            }
        }

        $resultPreviousSheet = request()->session()->get('import_result');
        if(isset($resultPreviousSheet)){
            $resultCurrentSheet = [
                'err' => $err + $resultPreviousSheet['err'],
                'processed' => $processed + $resultPreviousSheet['processed'],
                'errors' => array_merge($errors, $resultPreviousSheet['error']),
            ];
        }else{
            $resultCurrentSheet = [
                'err' => $err,
                'processed' => $processed,
                'errors' => $errors,
            ];
        }
        request()->session()->flash('import_result', $resultCurrentSheet);
    }
}
