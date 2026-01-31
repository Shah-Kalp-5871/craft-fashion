<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tagId = $this->route('tag') ?? $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tags')->ignore($tagId)
            ],
            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('tags')->ignore($tagId)
            ],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'icon' => ['nullable', 'string', 'max:50'],
            'featured' => ['boolean'],
            'status' => ['required', 'in:0,1,true,false']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tag name is required.',
            'name.unique' => 'Tag name already exists.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'Slug already exists.',
            'color.regex' => 'Color must be a valid hex code (e.g., #3b82f6).',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.'
        ];
    }

    protected function prepareForValidation()
    {
        // Convert featured to boolean if present
        if ($this->has('featured')) {
            $this->merge([
                'featured' => filter_var($this->featured, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

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

        // Auto-generate slug if not provided but name is
        if (!$this->has('slug') && $this->has('name')) {
            $slug = strtolower($this->name);
            $slug = preg_replace('/[^a-z0-9 -]/', '', $slug);
            $slug = preg_replace('/\s+/', '-', $slug);
            $slug = preg_replace('/-+/', '-', $slug);

            $this->merge([
                'slug' => $slug
            ]);
        }

        // Ensure slug is lowercase
        if ($this->has('slug')) {
            $this->merge([
                'slug' => strtolower($this->slug)
            ]);
        }
    }
}
