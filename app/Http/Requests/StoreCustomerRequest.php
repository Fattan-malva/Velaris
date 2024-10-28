<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nrp' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'mapping' => 'required|string|max:50',
        ];
    }
}
