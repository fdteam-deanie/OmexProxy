<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBalanceRequest extends FormRequest
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
            'amount' => ['required', 'decimal:0,2']
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Please enter amount to deposit!',
            'amount.decimal' => 'Entered amount is not valid!',
        ];
    }
}
