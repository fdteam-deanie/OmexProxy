<?php

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class GetProxiesRequest extends FormRequest
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
            'filters.ip' => 'sometimes|nullable|string',
            'filters.is_online' => 'sometimes|nullable|boolean',
            'filters.is_paid' => 'sometimes|nullable|boolean',
            'filters.country' => 'sometimes|nullable|string',
            'filters.state' => 'sometimes|nullable|string',
            'filters.city' => 'sometimes|nullable|string',
            'filters.zip' => 'sometimes|nullable|string',
            'filters.isp' => 'sometimes|nullable|string',
            'filters.type' => 'sometimes|nullable|int',
            'filters.price' => 'sometimes|nullable|numeric',
        ];
    }
}
