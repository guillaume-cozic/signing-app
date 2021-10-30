<?php


namespace App\Signing\Signing\Domain\UseCases\RentalPackage\Impl;


use App\Signing\Signing\Application\ViewModel\ActionsSailorRentalPackageViewModel;
use App\Signing\Signing\Domain\Exceptions\SailorRentalPackageNotFound;
use App\Signing\Signing\Domain\Repositories\Read\ReadSailorRentalPackageRepository;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetActionsSailorRentalPackage;
use Carbon\Carbon;

class GetActionsSailorRentalPackageImpl implements GetActionsSailorRentalPackage
{
    public function __construct(
        private ReadSailorRentalPackageRepository $sailorRentalPackageRepository
    ){}

    public function execute(string $sailorRentalPackageId): array
    {
        $sailorRentalPackage = $this->sailorRentalPackageRepository->get($sailorRentalPackageId);
        if(!isset($sailorRentalPackage)) {
            throw new SailorRentalPackageNotFound();
        }
        foreach ($sailorRentalPackage->actions() as $action){
            $actions[] = new ActionsSailorRentalPackageViewModel(
                $action['type'],
                $action['hours'],
                Carbon::createFromFormat('Y-m-d H:i', $action['at_time'])
            );
        }
        return $actions ?? [];
    }
}
