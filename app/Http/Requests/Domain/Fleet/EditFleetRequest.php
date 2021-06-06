<?php


namespace App\Http\Requests\Domain\Fleet;

use Illuminate\Foundation\Http\FormRequest;

class EditFleetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'total_available' => 'required|min:0|integer',
        ];
    }
}
