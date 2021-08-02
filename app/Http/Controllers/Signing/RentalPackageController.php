<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;

class RentalPackageController extends Controller
{
    public function showAddRentalPackage()
    {
        return view('rental-package/add-form');
    }

    public function processAddRentalPackage()
    {
        return view('rental-package/add-form');
    }
}
