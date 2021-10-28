<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Shared\Services\UseCaseHandler\Parameters;
use App\Signing\Shared\Services\UseCaseHandler\UseCase;
use App\Signing\Signing\Application\ParametersWrapper\AddSubHoursSailorRentalPackageParameters;
use App\Signing\Signing\Domain\Exceptions\SailorRentalPackageNotFound;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\AddOrSubHoursToSailorRentalPackage;

class AddOrSubHoursToSailorRentalPackageImpl implements AddOrSubHoursToSailorRentalPackage, UseCase
{
    public function __construct(
        private SailorRentalPackageRepository $sailorRentalPackageRepository
    ){}

    public function execute(string $sailorRentalPackageId, float $hours)
    {
        $sailorRentalPackage = $this->sailorRentalPackageRepository->get($sailorRentalPackageId);
        if(!isset($sailorRentalPackage)){
            throw new SailorRentalPackageNotFound();
        }
        $sailorRentalPackage->addOrSubHours($hours);
    }

    public function handle(AddSubHoursSailorRentalPackageParameters|Parameters $parameters)
    {
        $this->execute($parameters->id, $parameters->hours);
    }
}
