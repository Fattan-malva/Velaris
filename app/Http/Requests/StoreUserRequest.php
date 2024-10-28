<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'nullable|string', // Removed min:8 validation
            'role' => 'required|string|max:255',
        ];
    }
}
