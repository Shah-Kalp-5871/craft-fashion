<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attributeId = $this->route('attribute') ?? $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('attributes')->ignore($attributeId)
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z_]+$/',
                Rule::unique('attributes')->ignore($attributeId)
            ],
            'type' => ['required', 'in:select,color,image,text'],
            'is_variant' => ['required', 'boolean'],
            'is_filterable' => ['required', 'boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:0,1,true,false'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Attribute name is required.',
            'name.unique' => 'Attribute name already exists.',
            'code.required' => 'Attribute code is required.',
            'code.unique' => 'Attribute code already exists.',
            'code.regex' => 'Code must contain only lowercase letters and underscores.',
            'type.required' => 'Attribute type is required.',
            'is_variant.required' => 'Variant field is required.',
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
            'is_variant' => filter_var($this->is_variant, FILTER_VALIDATE_BOOLEAN),
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
