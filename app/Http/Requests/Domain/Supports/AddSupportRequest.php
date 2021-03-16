<?php


namespace App\Http\Requests\Domain\Supports;

use Illuminate\Foundation\Http\FormRequest;

class AddSupportRequest extends FormRequest
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
            'state' => 'required',
        ];
    }
}
