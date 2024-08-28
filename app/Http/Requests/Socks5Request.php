<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Socks5Request extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [ 'required', 'max:12', 'regex:/^[a-zA-Z0-9_-]+$/' ],
            'password' => [ 'required', 'between:6,12', 'regex:/^[a-zA-Z0-9_-]+$/' ],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Please enter username for proxies',
            'username.max' => 'Username for proxies is too long',
            'username.regex' => 'Username contains invalid characters',
            'password.required' => 'Please enter password for proxies',
            'password.between' => 'Password for proxies can be from 6 to 12 characters',
            'password.regex' => 'Password contains invalid characters',
        ];
    }
}
