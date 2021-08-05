<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\RentalPackage\AddRentalPackageRequest;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;
use Ramsey\Uuid\Uuid;

class RentalPackageController extends Controller
{
    public function showAddRentalPackage(GetFleetsList $getFleetsList, GetRentalPackages $getRentalPackages)
    {
        $rentalPackages = $getRentalPackages->execute();
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]], 0, 0);
        return view('rental-package/index', [
            'fleets' => $fleets,
            'rentalPackages' => $rentalPackages
        ]);
    }

    public function processAddRentalPackage(AddRentalPackageRequest $request, CreateRentalPackage $createRentalPackage)
    {
        $fleets = $request->input('fleets');
        $name = $request->input('name');
        $validityInDay = $request->input('validity');

        $createRentalPackage->execute(Uuid::uuid4(), $fleets, $name, $validityInDay);
        return redirect()->route('rental-package.add');
    }
}
