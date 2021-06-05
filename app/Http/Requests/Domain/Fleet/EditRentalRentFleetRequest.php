<?php

namespace App\Http\Requests\Domain\Fleet;

use Illuminate\Foundation\Http\FormRequest;

class EditRentalRentFleetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rents.hours.*' => ['nullable', 'min:1', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'rents.hours.*.min' => 'Le prix doit être supérieur à 0',
        ];
    }
}
