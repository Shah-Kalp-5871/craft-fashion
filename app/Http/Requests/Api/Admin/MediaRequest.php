<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaRequest extends FormRequest
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
        $rules = [
            'alt_text' => ['nullable', 'string', 'max:255'],
        ];

        // For upload (POST)
        if ($this->isMethod('post')) {
            $rules['files'] = ['required', 'array', 'max:10']; // Max 10 files at once
            $rules['files.*'] = [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,webp,svg',
                'max:3072', // 3MB in KB
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ];
        }

        // For update (PUT)
        if ($this->isMethod('put')) {
            $rules['alt_text'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'files.required' => 'Please select at least one file to upload.',
            'files.max' => 'You can upload maximum 10 files at once.',
            'files.*.mimes' => 'Only JPG, JPEG, PNG, GIF, WEBP, SVG images are allowed.',
            'files.*.max' => 'Each file must be less than 3MB.',
            'files.*.dimensions' => 'Image dimensions must be between 100x100 and 5000x5000 pixels.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'files.*' => 'file',
            'alt_text' => 'alternative text',
        ];
    }
}
