<?php


namespace App\Http\Requests\Domain\RentalPackage;

use Illuminate\Foundation\Http\FormRequest;

class AddSailorRentalPackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'rental_package_id' => 'required',
            'hours' => 'required|min:0|integer',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nom',
            'rental_package_id' => 'forfait',
            'hours' => 'heures',
        ];
    }
}
