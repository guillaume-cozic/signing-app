<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Query;


interface GetActionsSailorRentalPackage
{
    public function execute(string $sailorRentalPackageId):array;
}
