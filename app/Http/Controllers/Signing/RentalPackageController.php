<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\RentalPackage\AddRentalPackageRequest;
use App\Http\Requests\Domain\RentalPackage\EditRentalPackageRequest;
use App\Signing\Shared\Services\UseCaseHandler\UseCaseHandler;
use App\Signing\Signing\Application\ParametersWrapper\RentalPackageParameters;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\EditRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackage;
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

        (new UseCaseHandler($createRentalPackage))->execute(new RentalPackageParameters(Uuid::uuid4(), $fleets, $name, $validityInDay));
        return redirect()->route('rental-package.add');
    }

    public function showEdit(string $rentalPackageId, GetFleetsList $getFleetsList, GetRentalPackage $getRentalPackage)
    {
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]], 0, 0);
        $rentalPackage = $getRentalPackage->execute($rentalPackageId);
        return view('rental-package.edit-form', [
            'fleets' => $fleets,
            'rentalPackage' => $rentalPackage
        ]);
    }

    public function processEditRentalPackage(string $rentalPackageId, EditRentalPackageRequest $request, EditRentalPackage $editRentalPackage)
    {
        $fleets = $request->input('fleets');
        $name = $request->input('name');
        $validityInDay = $request->input('validity');
        (new UseCaseHandler($editRentalPackage))->execute(new RentalPackageParameters($rentalPackageId, $fleets, $name, $validityInDay));
        return redirect()->route('rental-package.add');
    }
}
