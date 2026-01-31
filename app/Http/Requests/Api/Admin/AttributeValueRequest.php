<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $valueId = $this->route('value') ?? $this->route('id');

        return [
            'attribute_id' => ['required', 'exists:attributes,id'],
            'value' => [
                'required',
                'string',
                'max:100',
                Rule::unique('attribute_values')->where(function ($query) {
                    return $query->where('attribute_id', $this->attribute_id);
                })->ignore($valueId)
            ],
            'label' => ['required', 'string', 'max:100'],
            'color_code' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'image_id' => ['nullable', 'exists:media,id'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:0,1,true,false'],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'Attribute is required.',
            'attribute_id.exists' => 'Selected attribute does not exist.',
            'value.required' => 'Value is required.',
            'value.unique' => 'This value already exists for this attribute.',
            'label.required' => 'Label is required.',
            'color_code.regex' => 'Color must be a valid hex code (e.g., #FF0000).',
            'image_id.exists' => 'Selected image does not exist.',
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

        // Ensure color_code is uppercase
        if ($this->has('color_code')) {
            $this->merge([
                'color_code' => strtoupper($this->color_code)
            ]);
        }

        // Set default sort_order
        if (!$this->has('sort_order')) {
            $this->merge([
                'sort_order' => 0
            ]);
        }
    }
}
