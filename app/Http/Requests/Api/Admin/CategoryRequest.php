<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') ?? $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories')->ignore($categoryId)
            ],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('categories')->ignore($categoryId)
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image_id' => ['nullable', 'exists:media,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:0,1,true,false'],
            'featured' => ['required', 'in:0,1,true,false'],
            'show_in_nav' => ['required', 'in:0,1,true,false'],

            // For specification groups
            'spec_group_ids' => ['nullable', 'array'],
            'spec_group_ids.*' => ['exists:specification_groups,id'],

            // For attributes
            'attributes' => ['nullable', 'array'],
            'attributes.*.is_required' => ['boolean'],
            'attributes.*.is_filterable' => ['boolean'],
            'attributes.*.sort_order' => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'Category name already exists.',
            'slug.required' => 'Category slug is required.',
            'slug.unique' => 'Category slug already exists.',
            'slug.regex' => 'Slug can only contain lowercase letters, numbers, and hyphens.',
            'parent_id.exists' => 'Selected parent category does not exist.',
            'image_id.exists' => 'Selected image does not exist.',
            'status.required' => 'Status is required.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert status to proper format
        $this->merge([
            'status' => filter_var($this->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'featured' => filter_var($this->featured, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'show_in_nav' => filter_var($this->show_in_nav, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'sort_order' => $this->sort_order ? (int) $this->sort_order : 0,
        ]);

        // Ensure slug is lowercase
        if ($this->has('slug')) {
            $this->merge([
                'slug' => strtolower($this->slug)
            ]);
        }
    }
}
