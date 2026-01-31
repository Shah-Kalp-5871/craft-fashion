<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecificationValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $valueId = $this->route('value') ?? $this->route('id');

        return [
            'specification_id' => ['required', 'exists:specifications,id'],
            'value' => [
                'required',
                'string',
                'max:100',
                Rule::unique('specification_values')->where(function ($query) {
                    return $query->where('specification_id', $this->specification_id);
                })->ignore($valueId)
            ],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:0,1,true,false'],
        ];
    }

    public function messages(): array
    {
        return [
            'specification_id.required' => 'Specification is required.',
            'specification_id.exists' => 'Selected specification does not exist.',
            'value.required' => 'Value is required.',
            'value.unique' => 'This value already exists for this specification.',
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

        // Set default sort_order
        if (!$this->has('sort_order')) {
            $this->merge([
                'sort_order' => 0
            ]);
        }
    }
}
