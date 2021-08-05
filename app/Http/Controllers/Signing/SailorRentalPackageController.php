<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\RentalPackage\AddSailorRentalPackageRequest;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use Ramsey\Uuid\Uuid;

class SailorRentalPackageController extends Controller
{
    public function index()
    {
        return view('rental-package.sailor.index');
    }

    public function processAdd(AddSailorRentalPackageRequest $request, CreateSailorRentalPackage $createSailorRentalPackage)
    {
        $rentalPackageId = $request->input('rental_package_id');
        $name = $request->input('name');
        $hours = $request->input('hours');

        $createSailorRentalPackage->execute(Uuid::uuid4(), $rentalPackageId, $name, $hours);
    }
}
