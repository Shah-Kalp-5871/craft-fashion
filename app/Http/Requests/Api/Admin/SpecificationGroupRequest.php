<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SpecificationGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $groupId = $this->route('group') ?? $this->route('id');

        return [
            'name' => ['required', 'string', 'max:100'],
            'specification_ids' => ['nullable', 'array'],
            'specification_ids.*' => ['exists:specifications,id'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:0,1,true,false'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Group name is required.',
            'specification_ids.array' => 'Specifications must be an array.',
            'specification_ids.*.exists' => 'Selected specification does not exist.',
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

        // Ensure specification_ids is an array
        if ($this->has('specification_ids') && !is_array($this->specification_ids)) {
            $this->merge([
                'specification_ids' => explode(',', $this->specification_ids)
            ]);
        }
    }
}
