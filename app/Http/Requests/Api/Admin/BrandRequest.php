<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Middleware handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'name' => [
                $isCreate ? 'required' : 'sometimes',
                'string',
                'max:100',
                $isCreate ? 'unique:brands,name' : null,
            ],

            'slug' => [
                $isCreate ? 'required' : 'sometimes',
                'string',
                'max:150',
                Rule::unique('brands')->ignore($this->route('brand')),
            ],

            'status' => [
                $isCreate ? 'required' : 'sometimes',
                'in:active,inactive',
            ],

            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image'],
            'featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'website' => ['nullable', 'url'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
        ];
    }


    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Brand name is required.',
            'name.unique' => 'Brand name already exists.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'Slug already exists.',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be a JPG, JPEG, PNG, or WebP image.',
            'logo.max' => 'Logo must be less than 2MB.',
            'logo.dimensions' => 'Logo dimensions must be between 100x100 and 2000x2000 pixels.',
            'status.required' => 'Status is required.',
            'website.url' => 'Website must be a valid URL.',
            'email.email' => 'Email must be a valid email address.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'logo' => 'brand logo',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Auto-generate slug if not provided
        if ($this->has('name') && !$this->has('slug')) {
            $this->merge([
                'slug' => \Str::slug($this->name)
            ]);
        }

        // Ensure slug is always lowercase
        if ($this->has('slug')) {
            $this->merge([
                'slug' => strtolower($this->slug)
            ]);
        }

        // Convert featured to boolean
        if ($this->has('featured')) {
            $this->merge([
                'featured' => (bool) $this->featured
            ]);
        }
    }
}
