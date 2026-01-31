<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product with all related data
     */
    public function createProduct(array $data): array
    {
        DB::beginTransaction();

        try {
            Log::info('Starting product creation', ['data' => $data]);

            // 1. Create product
            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'product_type' => $data['product_type'],
                'brand_id' => $data['brand_id'] ?? null,
                'main_category_id' => $data['main_category_id'],
                'tax_class_id' => $data['tax_class_id'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'],
                'status' => $data['status'] ?? 'draft',
                'is_featured' => $data['is_featured'] ?? false,
                'is_new' => $data['is_new'] ?? false,
                'is_bestseller' => $data['is_bestseller'] ?? false,
                'cod_available' => $data['cod_available'] ?? false,
                'weight' => $data['weight'] ?? 0,
                'length' => $data['length'] ?? 0,
                'width' => $data['width'] ?? 0,
                'height' => $data['height'] ?? 0,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
                'canonical_url' => $data['canonical_url'] ?? null,
                'product_code' => $data['product_code'] ?? null,
            ]);

            Log::info('Product created', ['product_id' => $product->id]);

            // 2. Sync categories
            $this->syncCategories($product, $data);

            // 3. Sync tags
            $this->syncTags($product, $data);

            // 4. Handle specifications
            $this->syncSpecifications($product, $data);

            // 5. Handle product variants
            if ($product->product_type === 'simple') {
                $this->createSimpleProductVariant($product, $data);
            } else {
                $this->createConfigurableProductVariants($product, $data);
            }

            DB::commit();
            Log::info('Product creation completed successfully', ['product_id' => $product->id]);

            return [
                'success' => true,
                'product' => $product,
                'message' => 'Product created successfully'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Sync categories for product
     */
    private function syncCategories(Product $product, array $data): void
    {
        $categoryIds = $data['category_ids'] ?? [];
        if (!in_array($data['main_category_id'], $categoryIds)) {
            $categoryIds[] = $data['main_category_id'];
        }

        if (!empty($categoryIds)) {
            $syncData = [];
            foreach ($categoryIds as $categoryId) {
                $syncData[$categoryId] = [
                    'is_primary' => $categoryId == $data['main_category_id'] ? 1 : 0,
                    'sort_order' => 0
                ];
            }
            $product->categories()->sync($syncData);
            Log::info('Categories synced', ['product_id' => $product->id, 'category_ids' => $categoryIds]);
        }
    }

    /**
     * Sync tags for product
     */
    private function syncTags(Product $product, array $data): void
    {
        $tagIds = $data['tag_ids'] ?? [];
        $product->tags()->sync($tagIds);
        Log::info('Tags synced', ['product_id' => $product->id, 'tag_ids' => $tagIds]);
    }

    /**
     * Sync specifications for product
     */
    private function syncSpecifications(Product $product, array $data): void
    {
        $specificationsData = [];
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            foreach ($data['specifications'] as $specData) {
                if (!empty($specData['specification_id'])) {
                    $specificationsData[$specData['specification_id']] = [
                        'specification_value_id' => $specData['specification_value_id'] ?? null,
                        'custom_value' => $specData['custom_value'] ?? null,
                    ];
                }
            }
        }

        $product->specifications()->sync($specificationsData);
        Log::info('Specifications synced', ['product_id' => $product->id, 'count' => count($specificationsData)]);
    }

    /**
     * Create simple product variant
     */
    private function createSimpleProductVariant(Product $product, array $data): void
    {
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $data['sku'],
            'price' => $data['price'],
            'compare_price' => $data['compare_price'] ?? null,
            'cost_price' => $data['cost_price'] ?? null,
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'reserved_quantity' => 0,
            'stock_status' => ($data['stock_quantity'] ?? 0) > 0 ? 'in_stock' : 'out_of_stock',
            'is_default' => true,
            'status' => isset($data['status']) && $data['status'] === 'active' ? 1 : 0,
            'weight' => $data['weight'] ?? $product->weight,
            'length' => $data['length'] ?? $product->length,
            'width' => $data['width'] ?? $product->width,
            'height' => $data['height'] ?? $product->height,
        ]);

        Log::info('Simple variant created', ['variant_id' => $variant->id]);

        // Handle images for simple product variant
        $this->syncVariantImages($variant, $data);
    }

    /**
     * Create configurable product variants
     */
    private function createConfigurableProductVariants(Product $product, array $data): void
    {
        Log::info('Creating configurable variants', ['product_id' => $product->id, 'variant_count' => count($data['variants'] ?? [])]);

        if (isset($data['variants']) && is_array($data['variants'])) {
            $defaultVariantSet = false;

            foreach ($data['variants'] as $index => $variantData) {
                try {
                    // Generate combination hash
                    $combinationHash = null;
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        $combinationHash = $this->generateCombinationHash($variantData['attributes']);
                    }

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'combination_hash' => $combinationHash,
                        'price' => $variantData['price'],
                        'compare_price' => $variantData['compare_price'] ?? null,
                        'cost_price' => $variantData['cost_price'] ?? null,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'reserved_quantity' => 0,
                        'stock_status' => ($variantData['stock_quantity'] ?? 0) > 0 ? 'in_stock' : 'out_of_stock',
                        'is_default' => ($index === 0 && !$defaultVariantSet) || ($variantData['is_default'] ?? false),
                        'status' => isset($variantData['status'])
                            ? ($variantData['status'] === 'active' ? 1 : 0)
                            : 1,
                        'weight' => $variantData['weight'] ?? $product->weight,
                        'length' => $variantData['length'] ?? $product->length,
                        'width' => $variantData['width'] ?? $product->width,
                        'height' => $variantData['height'] ?? $product->height,
                    ]);

                    if ($variant->is_default) {
                        $defaultVariantSet = true;
                    }

                    Log::info('Variant created', [
                        'variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'is_default' => $variant->is_default
                    ]);

                    // Handle variant attributes
                    $this->syncVariantAttributes($variant, $variantData);

                    // Handle variant images
                    $this->syncVariantImages($variant, $variantData);

                } catch (\Exception $e) {
                    Log::error('Failed to create variant', [
                        'index' => $index,
                        'error' => $e->getMessage(),
                        'variant_data' => $variantData
                    ]);
                    throw $e;
                }
            }
        }
    }



    public function updateProduct(Product $product, array $data): array
    {
        DB::beginTransaction();

        try {
            Log::info('Starting product update', ['product_id' => $product->id, 'data' => $data]);

            // 1. Update product basic information
            $product->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'product_type' => $data['product_type'],
                'brand_id' => $data['brand_id'] ?? null,
                'main_category_id' => $data['main_category_id'],
                'tax_class_id' => $data['tax_class_id'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'],
                'status' => $data['status'] ?? 'draft',
                'is_featured' => $data['is_featured'] ?? false,
                'is_new' => $data['is_new'] ?? false,
                'is_bestseller' => $data['is_bestseller'] ?? false,
                'cod_available' => $data['cod_available'] ?? false,
                'weight' => $data['weight'] ?? 0,
                'length' => $data['length'] ?? 0,
                'width' => $data['width'] ?? 0,
                'height' => $data['height'] ?? 0,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
                'canonical_url' => $data['canonical_url'] ?? null,
                'product_code' => $data['product_code'] ?? null,
            ]);

            Log::info('Product basic info updated', ['product_id' => $product->id]);

            // 2. Sync categories
            $this->syncCategories($product, $data);

            // 3. Sync tags
            $this->syncTags($product, $data);

            // 4. Handle specifications
            $this->syncSpecifications($product, $data);

            // 5. Handle variants
            $this->handleVariantsUpdate($product, $data);

            DB::commit();
            Log::info('Product update completed successfully', ['product_id' => $product->id]);

            return [
                'success' => true,
                'product' => $product,
                'message' => 'Product updated successfully'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle variants update - delete existing and create new
     */
    // private function handleVariantsUpdate(Product $product, array $data): void
    // {
    //     // Delete existing variants and their relations
    //     $product->variants()->delete();

    //     // Create new variants based on product type
    //     if ($product->product_type === 'simple') {
    //         $this->createSimpleProductVariant($product, $data);
    //     } else {
    //         $this->createConfigurableProductVariants($product, $data);
    //     }
    // }

    /**
     * Get product data for edit form
     */
    public function getProductForEdit(Product $product): array
    {
        $product->load([
            'brand:id,name',
            'mainCategory:id,name',
            'categories:id,name',
            'taxClass:id,name,rate',
            'tags:id,name',
            'specifications' => function ($query) {
                $query->with([
                    'values:id,specification_id,value'
                ]);
            },


            'variants' => function ($query) {
                $query->with([
                    'attributes' => function ($q) {
    $q->with(['attribute:id,name']);
},
                   'images:id,path,full_url,thumb_url'

                ])->orderBy('is_default', 'desc');
            }
        ]);

        $defaultVariant = $product->defaultVariant;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'product_type' => $product->product_type,
            'product_code' => $product->product_code,
            'brand_id' => $product->brand_id,
            'main_category_id' => $product->main_category_id,
            'tax_class_id' => $product->tax_class_id,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'status' => $product->status,
            'is_featured' => (bool) $product->is_featured,
            'is_new' => (bool) $product->is_new,
            'is_bestseller' => (bool) $product->is_bestseller,
            'weight' => (float) $product->weight,
            'length' => (float) $product->length,
            'width' => (float) $product->width,
            'height' => (float) $product->height,
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'meta_keywords' => $product->meta_keywords,
            'canonical_url' => $product->canonical_url,
            'brand' => $product->brand,
            'main_category' => $product->mainCategory,
            'categories' => $product->categories,
            'tax_class' => $product->taxClass,
            'tags' => $product->tags,
            'specifications' => $this->formatSpecifications($product->specifications),
            'variants' => $this->formatVariants($product->variants),
            'default_variant' => $defaultVariant ? $this->formatDefaultVariant($defaultVariant) : null,
            'main_image' => $this->getMainProductImage($product),
            'gallery_images' => $this->getGalleryImages($product),
        ];
    }

    /**
     * Format specifications for edit form
     */
    private function formatSpecifications($specifications): array
    {
        $formatted = [];

        foreach ($specifications as $spec) {

            $value = null;

            // Priority 1: custom value
            if (!empty($spec->pivot->custom_value)) {
                $value = $spec->pivot->custom_value;
            }

            // Priority 2: selected specification value
            elseif (!empty($spec->pivot->specification_value_id)) {
                $selected = $spec->values
                    ->firstWhere('id', $spec->pivot->specification_value_id);

                $value = $selected?->value;
            }

            $formatted[] = [
                'specification_id' => $spec->id,
                'name' => $spec->name,
                'input_type' => $spec->input_type,
                'value' => $value,
                'specification_value_id' => $spec->pivot->specification_value_id,
            ];
        }

        return $formatted;
    }


    /**
     * Format variants for edit form
     */
    private function formatVariants($variants): array
    {
        $formatted = [];
        foreach ($variants as $variant) {
            $formatted[] = [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => (float) $variant->price,
                'compare_price' => $variant->compare_price ? (float) $variant->compare_price : null,
                'cost_price' => $variant->cost_price ? (float) $variant->cost_price : null,
                'stock_quantity' => $variant->stock_quantity,
                'stock_status' => $variant->stock_status,
                'status' => $variant->status,
                'is_default' => (bool) $variant->is_default,
                'attributes' => $variant->attributes->map(function ($attr) {
                    return [
                        'attribute_id' => $attr->pivot->attribute_id,
                        'attribute_name' => $attr->name,
                        'attribute_value_id' => $attr->pivot->attribute_value_id,
                        'value' => $attr->value,
                    ];
                })->toArray(),
                'images' => $variant->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'media_id' => $image->media_id,
'url' => $image->full_url ?? $image->path,
                        'is_primary' => (bool) $image->is_primary,
                    ];
                })->toArray(),
            ];
        }
        return $formatted;
    }

    /**
     * Format default variant
     */
    private function formatDefaultVariant(ProductVariant $variant): array
    {
        return [
            'id' => $variant->id,
            'sku' => $variant->sku,
            'price' => (float) $variant->price,
            'compare_price' => $variant->compare_price ? (float) $variant->compare_price : null,
            'cost_price' => $variant->cost_price ? (float) $variant->cost_price : null,
            'stock_quantity' => $variant->stock_quantity,
            'stock_status' => $variant->stock_status,
            'status' => $variant->status,
            'images' => $variant->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'media_id' => $image->media_id,
'url' => $image->full_url ?? $image->path,
                    'is_primary' => (bool) $image->is_primary,
                ];
            })->toArray(),
        ];
    }

    /**
     * Get main product image
     */
    private function getMainProductImage(Product $product): ?array
    {
        $defaultVariant = $product->defaultVariant;
        if (!$defaultVariant) {
            return null;
        }
$mainImage = $defaultVariant->images()
    ->where('variant_images.is_primary', true)
    ->first();

if ($mainImage) {
    return [
        'id' => $mainImage->id,
        'url' => $mainImage->full_url ?? $mainImage->path,
    ];
}


        return null;
    }

    /**
     * Get gallery images
     */
    private function getGalleryImages(Product $product): array
    {
        $defaultVariant = $product->defaultVariant;
        if (!$defaultVariant) {
            return [];
        }

        $galleryImages = $defaultVariant->images()->where('is_primary', false)->get();

       return $galleryImages->map(function ($image) {
    return [
        'id' => $image->id,
        'url' => $image->full_url ?? $image->path,
    ];
})->values()->toArray();

    }


    /**
     * Sync variant attributes
     */
    private function syncVariantAttributes(ProductVariant $variant, array $variantData): void
    {
        if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
            $attributesData = [];
            foreach ($variantData['attributes'] as $attributeData) {
                if (!empty($attributeData['attribute_id'])) {
                    $attributesData[] = [
                        'variant_id' => $variant->id,
                        'attribute_id' => $attributeData['attribute_id'],
                        'attribute_value_id' => $attributeData['attribute_value_id'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($attributesData)) {
                DB::table('variant_attributes')->insert($attributesData);
                Log::info('Variant attributes synced', [
                    'variant_id' => $variant->id,
                    'attributes_count' => count($attributesData)
                ]);
            }
        }
    }

    /**
     * Sync variant images
     */
    private function syncVariantImages(ProductVariant $variant, array $variantData): void
    {
        $imagesData = [];

        // Collect unique image IDs
        $imageIds = [];

        if (!empty($variantData['main_image_id'])) {
            $imageIds[] = $variantData['main_image_id'];
        }

        if (!empty($variantData['gallery_image_ids']) && is_array($variantData['gallery_image_ids'])) {
            $imageIds = array_merge($imageIds, $variantData['gallery_image_ids']);
        }

        // Remove duplicates
        $imageIds = array_values(array_unique($imageIds));

        // Clear existing images first
        DB::table('variant_images')->where('variant_id', $variant->id)->delete();

        foreach ($imageIds as $index => $imageId) {
            $imagesData[] = [
                'variant_id' => $variant->id,
                'media_id' => $imageId,
                'is_primary' => ($imageId == ($variantData['main_image_id'] ?? null)) ? 1 : 0,
                'sort_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($imagesData)) {
            DB::table('variant_images')->insert($imagesData);
        }

        Log::info('Variant images synced', [
            'variant_id' => $variant->id,
            'image_count' => count($imagesData)
        ]);
    }


    /**
     * Generate combination hash for variant
     */
    private function generateCombinationHash(array $attributes): string
    {
        $data = [];
        foreach ($attributes as $attribute) {
            if (isset($attribute['attribute_id'])) {
                $data[$attribute['attribute_id']] = $attribute['attribute_value_id'] ?? $attribute['value'] ?? null;
            }
        }
        ksort($data);
        return md5(json_encode($data));
    }

    /**
     * Get category specifications with groups
     */
    public function getCategorySpecifications(int $categoryId): array
    {
        try {
            Log::info('Getting category specifications', ['category_id' => $categoryId]);

            $category = Category::where('status', 1)
                ->with([
                    'specificationGroups' => function ($q) {
                        $q->where('status', 1)
                            ->orderBy('sort_order')
                            ->with([
                                'specifications' => function ($q) {
                                    $q->where('status', 1)
                                        ->orderBy('sort_order')
                                        ->with('values');
                                }
                            ]);
                    }
                ])
                ->findOrFail($categoryId);

            $result = [];

            foreach ($category->specificationGroups as $group) {

                if ($group->specifications->isEmpty()) {
                    continue;
                }

                $groupData = [
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'group_sort_order' => $group->pivot->sort_order,
                    'specifications' => []
                ];

                foreach ($group->specifications as $spec) {

                    $groupData['specifications'][] = [
                        'id' => $spec->id,
                        'name' => $spec->name,
                        'code' => $spec->code,
                        'input_type' => $spec->input_type,
                        'is_required' => (bool) $spec->is_required,
                        'is_filterable' => (bool) $spec->is_filterable,
                        'sort_order' => $spec->pivot->sort_order,

                        // IMPORTANT LOGIC
                        'values' => in_array($spec->input_type, ['select', 'multiselect', 'radio', 'checkbox'])
                            ? $spec->values->map(fn($v) => [
                                'id' => $v->id,
                                'value' => $v->value,
                                'sort_order' => $v->sort_order
                            ])->values()
                            : []
                    ];
                }

                $result[] = $groupData;
            }

            return $result;

        } catch (\Throwable $e) {
            Log::error('Failed to get category specifications', [
                'category_id' => $categoryId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }


    /**
     * Get category attributes for variants
     */
    public function getCategoryAttributes(int $categoryId): array
    {
        try {
            Log::info('Getting category attributes', ['category_id' => $categoryId]);

            $category = Category::where('status', 1)
                ->with([
                    'attributes' => function ($q) {
                        $q->where('attributes.status', 1)
                            ->where('attributes.is_variant', 1)
                            ->orderBy('category_attributes.sort_order')
                            ->with([
                                'values.image'
                            ]);
                    }
                ])
                ->findOrFail($categoryId);

            $result = [];

            foreach ($category->attributes as $attribute) {

                $options = $attribute->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                        'label' => $value->label,
                        'color_code' => $value->color_code,
                        'image_id' => $value->image_id,
                        'image_url' => $value->image
                            ? ($value->image->full_url ?? $value->image->path)
                            : null,
                        'sort_order' => $value->sort_order,
                    ];
                })->values();

                $result[] = [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'code' => $attribute->code,
                    'type' => $attribute->type,
                    'input_type' => $attribute->type, // select / color / image / text
                    'is_required' => (bool) $attribute->pivot->is_required,
                    'is_variant' => (bool) $attribute->is_variant,
                    'is_filterable' => (bool) $attribute->pivot->is_filterable,
                    'category_sort_order' => $attribute->pivot->sort_order,
                    'options' => $options,
                ];
            }

            return $result;

        } catch (\Throwable $e) {
            Log::error('Failed to get category attributes', [
                'category_id' => $categoryId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate variants based on selected attributes
     */
    public function generateVariants(array $data): array
    {
        try {
            Log::info('Generating variants', ['data' => $data]);

            $attributes = $data['attributes'] ?? [];
            $baseSku = $data['base_sku'] ?? 'PROD';
            $basePrice = $data['base_price'] ?? 0;

            if (empty($attributes)) {
                throw new \Exception('No attributes provided');
            }

            // Prepare attribute values for combination
            $attributeValues = [];
            foreach ($attributes as $attribute) {
                if (isset($attribute['values']) && count($attribute['values']) > 0) {
                    $attrValues = [];
                    foreach ($attribute['values'] as $value) {
                        $attrValues[] = [
                            'attribute_id' => $attribute['attribute_id'],
                            'attribute_name' => $attribute['attribute_name'],
                            'value_id' => $value['id'] ?? null,
                            'value' => $value['value'] ?? '',
                            'label' => $value['label'] ?? $value['value'] ?? '',
                        ];
                    }
                    $attributeValues[] = $attrValues;
                }
            }

            // Generate all combinations
            $combinations = $this->generateAllCombinations($attributeValues);
            Log::info('Generated combinations', ['count' => count($combinations)]);

            // Generate variants from combinations
            $variants = [];
            $skuCounter = 1;

            foreach ($combinations as $combination) {
                $combinationData = [];
                $combinationDisplay = [];
                $variantAttributes = [];
                $variantNameParts = [];

                foreach ($combination as $attr) {
                    $combinationData[] = $attr;
                    $combinationDisplay[] = $attr['attribute_name'] . ': ' . $attr['label'];

                    // Prepare attributes for variant_attributes table
                    $variantAttributes[] = [
                        'attribute_id' => $attr['attribute_id'],
                        'attribute_value_id' => $attr['value_id'],
                        'value' => $attr['value']
                    ];

                    $variantNameParts[] = $attr['label'];
                }

                // Create SKU: base + first letters of each attribute value
                $skuSuffix = '';
                foreach ($combination as $attr) {
                    $cleanValue = preg_replace('/[^a-z0-9]/i', '', $attr['value']);
                    $skuSuffix .= '-' . strtoupper(substr($cleanValue, 0, 3));
                }

                $sku = $baseSku . $skuSuffix . '-' . str_pad($skuCounter, 3, '0', STR_PAD_LEFT);

                $variants[] = [
                    'combination' => $combinationData,
                    'attributes' => $variantAttributes,
                    'combination_display' => implode(' | ', $combinationDisplay),
                    'variant_name' => implode(' ', $variantNameParts),
                    'sku' => $sku,
                    'price' => $basePrice,
                    'compare_price' => null,
                    'cost_price' => null,
                    'stock_quantity' => 0,
                    'status' => 'active',
                    'is_default' => $skuCounter === 1,
                ];

                $skuCounter++;
            }

            Log::info('Variants generated successfully', ['variant_count' => count($variants)]);

            return [
                'success' => true,
                'variants' => $variants,
                'total_variants' => count($variants),
                'message' => count($variants) . ' variants generated successfully',
            ];

        } catch (\Exception $e) {
            Log::error('Failed to generate variants', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate all combinations of attribute values
     */
    private function generateAllCombinations(array $attributeValues): array
    {
        if (empty($attributeValues)) {
            return [];
        }

        $result = [[]];

        foreach ($attributeValues as $values) {
            $temp = [];
            foreach ($result as $item) {
                foreach ($values as $value) {
                    $temp[] = array_merge($item, [$value]);
                }
            }
            $result = $temp;
        }

        return $result;
    }
    /**
     * Handle variants update (Create/Update/Delete)
     */
    private function handleVariantsUpdate(Product $product, array $data): void
    {
        // 1. Handle Simple Product
        if ($product->product_type === 'simple') {
            Log::info('Updating simple product variant', ['product_id' => $product->id]);
            $variant = $product->defaultVariant;
            
            if (!$variant) {
                Log::warning('No default variant found for simple product, creating one', ['product_id' => $product->id]);
                $this->createSimpleProductVariant($product, $data);
                return;
            }

            $variant->update([
                'sku' => $data['sku'] ?? $variant->sku,
                'price' => $data['price'] ?? $variant->price,
                'compare_price' => $data['compare_price'] ?? $variant->compare_price,
                'stock_quantity' => $data['stock_quantity'] ?? $variant->stock_quantity,
                'stock_status' => (isset($data['stock_quantity']) && $data['stock_quantity'] > 0) ? 'in_stock' : 'out_of_stock',
                'status' => 1,
                'weight' => $data['weight'] ?? $product->weight,
                'length' => $data['length'] ?? $product->length,
                'width' => $data['width'] ?? $product->width,
                'height' => $data['height'] ?? $product->height,
            ]);

            // Sync images for simple product (they are at the top level of $data)
            $this->syncVariantImages($variant, $data);
            Log::info('Simple product variant updated successfully', ['variant_id' => $variant->id]);
            return;
        }

        // 2. Handle Configurable Product
        if (!isset($data['variants']) || !is_array($data['variants'])) {
            Log::info('No variants data provided for configurable product update', ['product_id' => $product->id]);
            return;
        }

        Log::info('Updating configurable product variants', ['product_id' => $product->id, 'count' => count($data['variants'])]);
        $submittedVariantIds = [];

        foreach ($data['variants'] as $index => $variantData) {
            $variant = null;

            // Check if updating existing variant
            if (!empty($variantData['id'])) {
                $variant = ProductVariant::where('id', $variantData['id'])
                    ->where('product_id', $product->id)
                    ->first();
                if ($variant) {
                    $submittedVariantIds[] = $variant->id;
                }
            }

            if ($variant) {
                // UPDATE existing
                $variant->update([
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'compare_price' => $variantData['compare_price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'stock_status' => ($variantData['stock_quantity'] > 0) ? 'in_stock' : 'out_of_stock',
                    'is_default' => (isset($data['default_variant_index']) && $data['default_variant_index'] == $index) || ($variantData['is_default'] ?? 0),
                    'status' => 1
                ]);
                Log::debug('Variant updated', ['variant_id' => $variant->id, 'sku' => $variant->sku]);
            } else {
                // CREATE new
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'compare_price' => $variantData['compare_price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'stock_status' => ($variantData['stock_quantity'] > 0) ? 'in_stock' : 'out_of_stock',
                    'is_default' => (isset($data['default_variant_index']) && $data['default_variant_index'] == $index) || ($variantData['is_default'] ?? 0),
                    'status' => 1
                ]);
                $submittedVariantIds[] = $variant->id;
                Log::debug('New variant created during update', ['variant_id' => $variant->id, 'sku' => $variant->sku]);
                
                if (isset($variantData['attributes'])) {
                    $this->syncVariantAttributes($variant, $variantData);
                }
            }

            // Sync Images for both New and Existing
            $this->syncVariantImages($variant, $variantData);
        }

        // 3. Remove variants not in submission (if any deletions were intended)
        if (!empty($submittedVariantIds)) {
            $deletedCount = $product->variants()
                ->whereNotIn('id', $submittedVariantIds)
                ->delete();
            if ($deletedCount > 0) {
                Log::info('Deleted variants not in submission', ['product_id' => $product->id, 'count' => $deletedCount]);
            }
        }
    }

}
