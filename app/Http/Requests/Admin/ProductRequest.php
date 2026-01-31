<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        $rules = [
            // Basic Information
            'name' => 'required|string|max:200',
            'slug' => [
                'required',
                'string',
                'max:200',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'product_type' => 'required|in:simple,configurable',
            'product_code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'product_code')->ignore($productId),
            ],

            // Category & Brand
            'main_category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',

            // Tax
            'tax_class_id' => 'nullable|exists:tax_classes,id',

            // Description
            'short_description' => 'nullable|string',
            'description' => 'required|string',

            // Status & Flags
            'status' => 'required|in:draft,pending,active,inactive',
            'is_featured' => 'sometimes|boolean',
            'is_new' => 'sometimes|boolean',
            'is_bestseller' => 'sometimes|boolean',
            'cod_available' => 'sometimes|boolean',

            // Dimensions
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:500',

            // Tags
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',

            // Images
            'main_image_id' => 'nullable|exists:media,id',
            'gallery_image_ids' => 'nullable|array',
            'gallery_image_ids.*' => 'exists:media,id',

            // Specifications
            'specifications' => 'nullable|array',
            'specifications.*.specification_id' => 'required|exists:specifications,id',
            'specifications.*.specification_value_id' => 'nullable|exists:specification_values,id',
            'specifications.*.custom_value' => 'nullable|string',
        ];

        // Simple product validation
        if ($this->product_type === 'simple') {
            $rules['sku'] = [
                'required',
                'string',
                'max:100',
                // Don't use ignore here for create, only for update
                ($this->isMethod('PUT') || $this->isMethod('PATCH'))
                ? Rule::unique('product_variants', 'sku')->ignore($productId, 'product_id')
                : 'unique:product_variants,sku'
            ];
            $rules['price'] = 'required|numeric|min:0';
            $rules['compare_price'] = 'nullable|numeric|min:0';
            $rules['cost_price'] = 'nullable|numeric|min:0';
            $rules['stock_quantity'] = 'required|integer|min:0';
            $rules['stock_status'] = 'nullable|in:in_stock,out_of_stock,backorder,preorder';
        }

        // Configurable product validation
        if ($this->product_type === 'configurable') {
            $rules['variants'] = 'required|array|min:1';

            // Check if we're updating and get variant IDs
            $variantIds = [];
            if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
                // If it's a standard resource route, $productId is the ID.
                // But wait, the ignore logic for simple product variants used `product_id`.
                // For configurable products, we need to ignore the variant IDs themselves.
            }

            foreach ($this->variants ?? [] as $index => $variant) {
                $variantId = $variant['id'] ?? null;

                $rules["variants.{$index}.sku"] = [
                    'required',
                    'string',
                    'max:100',
                    $variantId
                    ? Rule::unique('product_variants', 'sku')->ignore($variantId)
                    : 'unique:product_variants,sku'
                ];
                $rules["variants.{$index}.price"] = 'required|numeric|min:0';
                $rules["variants.{$index}.compare_price"] = 'nullable|numeric|min:0';
                $rules["variants.{$index}.cost_price"] = 'nullable|numeric|min:0';
                $rules["variants.{$index}.stock_quantity"] = 'required|integer|min:0';
                $rules["variants.{$index}.status"] = 'required|in:active,inactive';
                $rules["variants.{$index}.is_default"] = 'sometimes|boolean';
                $rules["variants.{$index}.combination"] = 'nullable|array';

                // Variant attributes
                $rules["variants.{$index}.attributes"] = 'nullable|array';
                $rules["variants.{$index}.attributes.*.attribute_id"] = 'required|exists:attributes,id';
                $rules["variants.{$index}.attributes.*.attribute_value_id"] = 'required|exists:attribute_values,id';
                $rules["variants.{$index}.attributes.*.value"] = 'nullable|string';

                // Variant images
                $rules["variants.{$index}.main_image_id"] = 'nullable|exists:media,id';
                $rules["variants.{$index}.gallery_image_ids"] = 'nullable|array';
                $rules["variants.{$index}.gallery_image_ids.*"] = 'exists:media,id';
            }
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        $attributes = [
            'name' => 'product name',
            'slug' => 'product slug',
            'main_category_id' => 'main category',
            'brand_id' => 'brand',
            'sku' => 'SKU',
            'price' => 'price',
        ];

        // Add variant field attributes
        if ($this->product_type === 'configurable' && $this->has('variants')) {
            foreach ($this->variants as $index => $variant) {
                $attributes["variants.{$index}.sku"] = "variant SKU #" . ($index + 1);
                $attributes["variants.{$index}.price"] = "variant price #" . ($index + 1);
                $attributes["variants.{$index}.stock_quantity"] = "variant stock #" . ($index + 1);
            }
        }

        return $attributes;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'slug.unique' => 'This URL slug is already in use',
            'product_code.unique' => 'This product code is already in use',
            'sku.unique' => 'This SKU is already in use',
            'variants.*.sku.unique' => 'Variant SKU is already in use',
            'main_category_id.required' => 'Please select a main category',
            'description.required' => 'Product description is required',
            'variants.required' => 'At least one variant is required for configurable products',
            'compare_price.gt' => 'Compare price must be greater than regular price',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure boolean fields are properly cast
        $this->merge([
            'is_featured' => $this->has('is_featured') ? (bool) $this->is_featured : false,
            'is_new' => $this->has('is_new') ? (bool) $this->is_new : false,
            'is_bestseller' => $this->has('is_bestseller') ? (bool) $this->is_bestseller : false,
            'cod_available' => $this->has('cod_available') ? (bool) $this->cod_available : false,
        ]);

        // Handle arrays - ensure they're empty arrays instead of null
        if (!$this->has('category_ids') || empty($this->category_ids)) {
            $this->merge(['category_ids' => []]);
        }

        if (!$this->has('tag_ids') || empty($this->tag_ids)) {
            $this->merge(['tag_ids' => []]);
        }

        if (!$this->has('gallery_image_ids') || empty($this->gallery_image_ids)) {
            $this->merge(['gallery_image_ids' => []]);
        }

        if (!$this->has('specifications') || empty($this->specifications)) {
            $this->merge(['specifications' => []]);
        }

        // For configurable products, ensure variants have is_default flag
        if ($this->product_type === 'configurable' && $this->has('variants')) {
            $variants = $this->variants;
            $hasDefault = false;

            foreach ($variants as $index => &$variant) {
                // Set first variant as default if none is set
                if ($index === 0 && !isset($variant['is_default'])) {
                    $variant['is_default'] = true;
                    $hasDefault = true;
                } elseif (isset($variant['is_default']) && $variant['is_default']) {
                    $hasDefault = true;
                }

                // Ensure required fields exist
                $variant['status'] = $variant['status'] ?? 'active';
                $variant['stock_quantity'] = $variant['stock_quantity'] ?? 0;
                $variant['attributes'] = $variant['attributes'] ?? [];
                $variant['gallery_image_ids'] = $variant['gallery_image_ids'] ?? [];
            }

            // If no default variant, set first one
            if (!$hasDefault && count($variants) > 0) {
                $variants[0]['is_default'] = true;
            }

            $this->merge(['variants' => $variants]);
        }
    }
}
