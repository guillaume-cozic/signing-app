<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\RentalPackage\AddSailorRentalPackageRequest;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;
use Ramsey\Uuid\Uuid;

class SailorRentalPackageController extends Controller
{
    public function index(GetRentalPackages $getRentalPackages)
    {
        $rentalPackages = $getRentalPackages->execute();
        return view('rental-package.sailor.index', [
            'rentalPackages' => $rentalPackages
        ]);
    }

    public function processAdd(AddSailorRentalPackageRequest $request, CreateSailorRentalPackage $createSailorRentalPackage)
    {
        $rentalPackageId = $request->input('rental_package_id');
        $name = $request->input('name');
        $hours = $request->input('hours');


        $createSailorRentalPackage->execute(Uuid::uuid4(), $rentalPackageId, $name, $hours);
        return redirect()->route('sailor-rental-package.index');
    }

    public function listSailorRentalPackage()
    {

    }
}
