<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    \Log::info('TaxClassRequest rules() called');

    return [
        'name' => ['required', 'string', 'max:100'],
        'code' => [
            'nullable', 
            'string', 
            'max:50', 
            Rule::unique('tax_classes', 'code')->ignore($this->route('id'))
        ],
        'description' => ['nullable', 'string'],
        'sort_order' => ['integer', 'min:0'],
        'is_active' => ['boolean'],
        'tax_rate_ids' => ['array'],
        'tax_rate_ids.*' => ['exists:tax_rates,id'],
    ];
}



    public function messages(): array
    {
        return [
            'name.required' => 'Tax class name is required.',
            'name.unique' => 'Tax class name already exists.',
            'code.required' => 'Tax code is required.',
            'code.unique' => 'Tax code already exists.',
        ];
    }
}
