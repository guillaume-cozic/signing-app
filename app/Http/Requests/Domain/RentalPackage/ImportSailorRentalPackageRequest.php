<?php


namespace App\Http\Requests\Domain\RentalPackage;

use Illuminate\Foundation\Http\FormRequest;

class ImportSailorRentalPackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'import-file' => 'required|mimes:xls,xlsx',
        ];
    }

    public function attributes()
    {
        return [
            'import-file' => 'fichier d\'import',
        ];
    }
}
