<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $taxRateId = $this->route('tax_rate');

        return [
            'tax_class_id' => ['required', 'exists:tax_classes,id'],
            'name' => ['required', 'string', 'max:100'],
            'country_code' => ['nullable', 'string', 'max:2'],
            'state_code' => ['nullable', 'string', 'max:10'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
            'priority' => ['integer', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'tax_class_id.required' => 'Tax class is required.',
            'tax_class_id.exists' => 'Selected tax class does not exist.',
            'name.required' => 'Tax rate name is required.',
            'rate.required' => 'Tax rate percentage is required.',
            'rate.numeric' => 'Tax rate must be a number.',
            'rate.min' => 'Tax rate cannot be negative.',
            'rate.max' => 'Tax rate cannot exceed 100%.',
        ];
    }

    protected function prepareForValidation()
    {
        // Ensure country_code is uppercase
        if ($this->has('country_code')) {
            $this->merge([
                'country_code' => strtoupper($this->country_code)
            ]);
        }

        // Ensure state_code is uppercase
        if ($this->has('state_code')) {
            $this->merge([
                'state_code' => strtoupper($this->state_code)
            ]);
        }

        // Set default priority if not provided
        if (!$this->has('priority')) {
            $this->merge([
                'priority' => 0
            ]);
        }

        // Set default is_active if not provided
        if (!$this->has('is_active')) {
            $this->merge([
                'is_active' => true
            ]);
        }
    }
}
