<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => [ 'required', 'string' ],
            'password' => [ 'required', 'string' ],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Please enter your username',
            'password.required' => 'Please enter password',
        ];
    }
}
