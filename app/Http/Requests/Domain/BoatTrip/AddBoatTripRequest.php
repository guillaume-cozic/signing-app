<?php

namespace App\Http\Requests\Domain\BoatTrip;

use Illuminate\Foundation\Http\FormRequest;

class AddBoatTripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
