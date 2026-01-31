<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $specificationId = $this->route('specification') ?? $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('specifications')->ignore($specificationId)
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z_]+$/',
                Rule::unique('specifications')->ignore($specificationId)
            ],
            'input_type' => ['required', 'in:select,text,textarea,multiselect,radio,checkbox'],
            'is_required' => ['required', 'boolean'],
            'is_filterable' => ['required', 'boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:0,1,true,false'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Specification name is required.',
            'name.unique' => 'Specification name already exists.',
            'code.required' => 'Specification code is required.',
            'code.unique' => 'Specification code already exists.',
            'code.regex' => 'Code must contain only lowercase letters and underscores.',
            'input_type.required' => 'Input type is required.',
            'is_required.required' => 'Required field is required.',
            'status.required' => 'Status is required.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert status to proper format (0 or 1)
        if ($this->has('status')) {
            $status = $this->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $this->merge([
                    'status' => filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0
                ]);
            } elseif (is_numeric($status)) {
                $this->merge([
                    'status' => (int) $status
                ]);
            }
        }

        // Convert booleans
        $this->merge([
            'is_required' => filter_var($this->is_required, FILTER_VALIDATE_BOOLEAN),
            'is_filterable' => filter_var($this->is_filterable, FILTER_VALIDATE_BOOLEAN),
        ]);

        // Ensure code is lowercase
        if ($this->has('code')) {
            $this->merge([
                'code' => strtolower($this->code)
            ]);
        }
    }
}
