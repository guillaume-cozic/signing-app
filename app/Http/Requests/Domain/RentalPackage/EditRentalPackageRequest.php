<?php


namespace App\Http\Requests\Domain\RentalPackage;

use Illuminate\Foundation\Http\FormRequest;

class EditRentalPackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'fleets' => 'required',
            'validity' => 'required|min:0|integer',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nom',
            'fleets' => 'flottes',
            'validity' => 'durée de validité',
        ];
    }
}
