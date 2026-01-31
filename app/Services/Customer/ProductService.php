<?php

namespace App\Services\Customer;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\Specification;
use App\Models\ProductVariant;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Get all available filters for products
     */
    public function getAllFilters(): array
    {
        $cacheKey = 'all_products_filters_' . config('app.locale');

        return Cache::remember($cacheKey, 3600, function () {
            $filters = [];

            // Price range
            $priceRange = DB::table('products')
                ->join('product_variants', function ($join) {
                    $join->on('products.id', '=', 'product_variants.product_id')
                        ->where('product_variants.is_default', true)
                        ->where('product_variants.status', 1);
                })
                ->where('products.status', 'active')
                ->selectRaw('MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price')
                ->first();
            $filters['price_range'] = [
                'min' => (int)($priceRange->min_price ?? 0),
                'max' => (int)($priceRange->max_price ?? 50000),
            ];

            // Categories
            $filters['categories'] = Category::where('status', 1)
                ->whereNull('parent_id')
                ->withCount([
                    'products' => function ($query) {
                        $query->where('products.status', 'active');
                    }
                ])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'count' => $category->products_count
                    ];
                })
                ->toArray();

            // Brands
            $filters['brands'] = Brand::where('status', 1)
                ->withCount([
                    'products' => function ($query) {
                        $query->where('products.status', 'active');
                    }
                ])
                ->orderBy('name')
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'count' => $brand->products_count
                    ];
                })
                ->toArray();

            // Filterable Attributes (for variant filtering)
            $filters['attributes'] = Attribute::where('status', 1)
                ->where('is_filterable', 1)
                ->with([
                    'values' => function ($query) {
                        $query->where('status', 1)
                            ->orderBy('sort_order');
                    }
                ])
                ->orderBy('sort_order')
                ->get()
                ->map(function ($attribute) {
                    $values = $attribute->values->map(function ($value) use ($attribute) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                            'label' => $value->label,
                            'color_code' => $value->color_code,
                            'image_id' => $value->image_id,
                            'product_count' => $this->getAttributeValueProductCount($attribute->id, $value->id),
                        ];
                    })->toArray();

                    return [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'code' => $attribute->code,
                        'type' => $attribute->type,
                        'values' => array_filter($values, fn($v) => $v['product_count'] > 0),
                    ];
                })
                ->filter(fn($attr) => !empty($attr['values']))
                ->toArray();

            // Filterable Specifications
            $filters['specifications'] = Specification::where('status', 1)
                ->where('is_filterable', 1)
                ->with([
                    'values' => function ($query) {
                        $query->where('status', 1)
                            ->orderBy('sort_order');
                    }
                ])
                ->orderBy('sort_order')
                ->get()
                ->map(function ($spec) {
                    $values = $spec->values->map(function ($value) use ($spec) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                            'product_count' => $this->getSpecificationValueProductCount($spec->id, $value->id),
                        ];
                    })->toArray();

                    // Also include custom values
                    $customValues = DB::table('product_specifications')
                        ->where('specification_id', $spec->id)
                        ->whereNotNull('custom_value')
                        ->select('custom_value as value', DB::raw('COUNT(*) as product_count'))
                        ->groupBy('custom_value')
                        ->orderBy('custom_value')
                        ->get()
                        ->map(function ($item) {
                        return [
                            'id' => null,
                            'value' => $item->value,
                            'product_count' => $item->product_count,
                        ];
                    })
                        ->toArray();

                    $allValues = array_merge($values, $customValues);

                    return [
                        'id' => $spec->id,
                        'name' => $spec->name,
                        'code' => $spec->code,
                        'input_type' => $spec->input_type,
                        'values' => array_filter($allValues, fn($v) => $v['product_count'] > 0),
                    ];
                })
                ->filter(fn($spec) => !empty($spec['values']))
                ->toArray();

            return $filters;
        });
    }

    /**
     * Get product count for attribute value
     */
    private function getAttributeValueProductCount($attributeId, $valueId): int
    {
        return DB::table('variant_attributes')
            ->join('product_variants', 'variant_attributes.variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('variant_attributes.attribute_id', $attributeId)
            ->where('variant_attributes.attribute_value_id', $valueId)
            ->where('products.status', 'active')
            ->count();
    }

    /**
     * Get product count for specification value
     */
    private function getSpecificationValueProductCount($specificationId, $valueId): int
    {
        return DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.id')
            ->where('product_specifications.specification_id', $specificationId)
            ->where('product_specifications.specification_value_id', $valueId)
            ->where('products.status', 'active')
            ->count();
    }

    /**
     * Get products with filters
     */
    public function getProducts(array $filters = [], int $perPage = 12, int $page = 1): LengthAwarePaginator
    {
        try {
            $query = Product::query()
                ->with([
                    'brand:id,name',
                    'mainCategory:id,name,slug',
                    'variants' => function ($query) {
                        $query->select(
                            'id',
                            'product_id',
                            'sku',
                            'price',
                            'compare_price',
                            'cost_price',
                            'stock_quantity',
                            'stock_status',
                            'is_default',
                            'status'
                        )
                            ->where('is_default', true)
                            ->with([
                                'images' => function ($q) {
                                    $q->select('media.id', 'media.file_path', 'media.thumbnails', 'media.disk')
                                        ->orderBy('variant_images.is_primary', 'desc')
                                        ->orderBy('variant_images.sort_order');
                                }
                            ]);
                    },
                    'specifications' => function ($query) {
                        $query->select('specifications.id', 'specifications.name', 'specifications.code')
                            ->withPivot('custom_value', 'specification_value_id')
                            ->with([
                                'values' => function ($q) {
                                    $q->select('id', 'specification_id', 'value');
                                }
                            ]);
                    },
                ])
                ->where('products.status', 'active');

            // Search filter
            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('products.name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('products.description', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('products.short_description', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('products.product_code', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('brand', function ($q) use ($searchTerm) {
                            $q->where('name', 'LIKE', "%{$searchTerm}%");
                        });
                });
            }

            // Category filter
            if (!empty($filters['category_id'])) {
                $categoryIds = is_array($filters['category_id']) ? $filters['category_id'] : [$filters['category_id']];
                $query->where(function ($q) use ($categoryIds) {
                    $q->whereIn('products.main_category_id', $categoryIds)
                        ->orWhereHas('categories', function ($q2) use ($categoryIds) {
                            $q2->whereIn('categories.id', $categoryIds);
                        });
                });
            }

            // Brand filter
            if (!empty($filters['brand_id'])) {
                $brandIds = is_array($filters['brand_id']) ? $filters['brand_id'] : [$filters['brand_id']];
                $query->whereIn('products.brand_id', $brandIds);
            }

            // Price range filter
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->where('is_default', true);
                if (!empty($filters['min_price'])) {
                    $q->where('price', '>=', max(0, (int)$filters['min_price']));
                }
                if (!empty($filters['max_price'])) {
                    $q->where('price', '<=', max(0, (int)$filters['max_price']));
                }
            });

            // Attribute filter
            if (!empty($filters['attribute_value'])) {
                $query->whereHas('variants.variantAttributes', function ($q) use ($filters) {
                    if (!empty($filters['attribute'])) {
                        $q->whereHas('attribute', function ($q2) use ($filters) {
                            $q2->where('code', $filters['attribute']);
                        });
                    }
                    
                    if (is_numeric($filters['attribute_value'])) {
                        $q->where('attribute_value_id', $filters['attribute_value']);
                    } else {
                        $q->whereHas('attributeValue', function ($q2) use ($filters) {
                            $q2->where('value', $filters['attribute_value']);
                        });
                    }
                });
            }

            // Specification filter
            if (!empty($filters['specification_value'])) {
                $query->whereHas('specifications', function ($q) use ($filters) {
                    if (!empty($filters['specification'])) {
                        $q->where(function ($q2) use ($filters) {
                            $q2->where('specifications.code', $filters['specification'])
                                ->orWhere('specifications.name', 'LIKE', "%{$filters['specification']}%");
                        });
                    }
                    
                    $q->where(function ($q2) use ($filters) {
                        if (is_numeric($filters['specification_value'])) {
                            $q2->where('product_specifications.specification_value_id', $filters['specification_value']);
                        } else {
                            $q2->where('product_specifications.custom_value', $filters['specification_value'])
                                ->orWhereHas('values', function ($q3) use ($filters) {
                                    $q3->where('value', $filters['specification_value']);
                                });
                        }
                    });
                });
            }

            // Stock filter
            if (!empty($filters['in_stock']) && $filters['in_stock']) {
                $query->whereHas('variants', function ($q) {
                    $q->where('is_default', true)
                        ->where('stock_quantity', '>', 0);
                });
            }

            // Special filters
            if (!empty($filters['is_featured']) && $filters['is_featured']) {
                $query->where('is_featured', true);
            }
            if (!empty($filters['is_new']) && $filters['is_new']) {
                $query->where('is_new', true);
            }
            if (!empty($filters['is_bestseller']) && $filters['is_bestseller']) {
                $query->where('is_bestseller', true);
            }

            // Apply sorting
            $this->applySorting($query, $filters['sort_by'] ?? 'newest');

            // Execute query with pagination
            $products = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform products
            $products->getCollection()->transform(function ($product) {
                return $this->transformProductForListing($product);
            });

            return $products;

        } catch (\Exception $e) {
            Log::error('ProductService getProducts error: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            return new LengthAwarePaginator([], 0, $perPage, $page);
        }
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query, $sortBy): void
    {
        switch ($sortBy) {
            case 'price_asc':
                $query->select('products.*')
                    ->join('product_variants', function ($join) {
                        $join->on('products.id', '=', 'product_variants.product_id')
                            ->where('product_variants.is_default', true);
                    })
                    ->orderBy('product_variants.price');
                break;

            case 'price_desc':
                $query->select('products.*')
                    ->join('product_variants', function ($join) {
                        $join->on('products.id', '=', 'product_variants.product_id')
                            ->where('product_variants.is_default', true);
                    })
                    ->orderBy('product_variants.price', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('products.name');
                break;

            case 'name_desc':
                $query->orderBy('products.name', 'desc');
                break;

            case 'featured':
                $query->orderBy('products.is_featured', 'desc')
                    ->orderBy('products.created_at', 'desc');
                break;

            case 'popular':
                $query->orderBy('products.is_bestseller', 'desc');
                break;

            case 'newest':
            default:
                $query->orderBy('products.created_at', 'desc');
                break;
        }
    }

    /**
     * Transform product for listing view
     */
    private function transformProductForListing(Product $product): array
    {
        $defaultVariant = $product->variants->where('is_default', true)->first();
        $mainImage = '/images/placeholder-product.jpg';

        // Get main image
        if ($defaultVariant && $defaultVariant->images && $defaultVariant->images->isNotEmpty()) {
            $primaryImage = $defaultVariant->images->where('pivot.is_primary', true)->first();
            if (!$primaryImage) {
                $primaryImage = $defaultVariant->images->first();
            }
            if ($primaryImage) {
                $mainImage = $primaryImage->full_url ?? $primaryImage->thumb_url ?? $primaryImage->file_path;
            }
        }

        // Calculate discount
        $discountPercent = 0;
        if ($defaultVariant && $defaultVariant->compare_price && $defaultVariant->compare_price > $defaultVariant->price) {
            $discountPercent = round((($defaultVariant->compare_price - $defaultVariant->price) / $defaultVariant->compare_price) * 100);
        }

        // Get materials from specifications
        $materials = [];
        foreach ($product->specifications as $spec) {
            if (
                str_contains(strtolower($spec->name), 'material') ||
                str_contains(strtolower($spec->code), 'material')
            ) {
                if ($spec->pivot->custom_value) {
                    $materials[] = $spec->pivot->custom_value;
                } else if ($spec->pivot->specification_value_id) {
                    $value = $spec->values->firstWhere('id', $spec->pivot->specification_value_id);
                    if ($value) {
                        $materials[] = $value->value;
                    }
                }
            }
        }

        // Stock status
        $isInStock = $defaultVariant && $defaultVariant->stock_quantity > 0;

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'short_description' => $product->short_description,
            'main_image' => $mainImage,
            'price' => $defaultVariant ? (float) $defaultVariant->price : 0,
            'compare_price' => $defaultVariant ? (float) $defaultVariant->compare_price : null,
            'discount_percent' => $discountPercent,
            'is_in_stock' => $isInStock,
            'stock_quantity' => $defaultVariant ? $defaultVariant->stock_quantity : 0,
            'rating' => (float) ($product->rating ?? 4.5),
            'review_count' => (int) ($product->review_count ?? rand(10, 200)),
            'is_featured' => (bool) $product->is_featured,
            'is_new' => (bool) $product->is_new,
            'is_bestseller' => (bool) $product->is_bestseller,
            'has_variants' => $product->product_type === 'configurable',
            'variants_count' => $product->variants->count(),
            'default_variant_id' => $defaultVariant ? $defaultVariant->id : null,
            'materials' => $materials,
            'brand' => $product->brand ? $product->brand->name : null,
            'brand_id' => $product->brand_id,
            'category' => $product->mainCategory ? $product->mainCategory->name : null,
            'category_slug' => $product->mainCategory ? $product->mainCategory->slug : null,
            'created_at' => $product->created_at,
        ];
    }

    /**
     * Get product by slug for details view
     */
    public function getProductBySlug($slug)
    {
        try {
            $product = Product::with([
                'brand:id,name,description',
                'mainCategory:id,name,slug',
                'variants' => function ($query) {
                    $query->select(
                        'id',
                        'product_id',
                        'sku',
                        'price',
                        'compare_price',
                        'cost_price',
                        'stock_quantity',
                        'stock_status',
                        'is_default',
                        'status',
                        'weight'
                    )
                        ->with([
                            'images' => function ($q) {
                              $q->select('media.id', 'media.file_path', 'media.thumbnails', 'media.disk')
                                    ->orderBy('variant_images.is_primary', 'desc')
                                    ->orderBy('variant_images.sort_order');
                            },
                            'variantAttributes.attribute:id,name,code,type',
'variantAttributes.attributeValue:id,value,label,color_code'

                        ]);
                },
                'specifications' => function ($query) {
                    $query->select('specifications.id', 'specifications.name', 'specifications.code')
                        ->withPivot('custom_value', 'specification_value_id')
                        ->with([
                            'values' => function ($q) {
                                $q->select('id', 'specification_id', 'value');
                            }
                        ]);
                },
                'categories:id,name,slug',
            ])
                ->where('slug', $slug)
                ->where('status', 'active')
                ->firstOrFail();

            return $this->transformProductForDetails($product);

        } catch (\Exception $e) {
            Log::error('ProductService getProductBySlug error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Transform product for details view
     */
    private function transformProductForDetails(Product $product): array
    {
        $defaultVariant = $product->variants->where('is_default', true)->first();

        // Get all images
        $allImages = collect();
        foreach ($product->variants as $variant) {
            foreach ($variant->images as $image) {
                $allImages->push([
                    'id' => $image->id,
                    'url' => $image->full_url ?? $image->thumb_url ?? $image->file_path,
                    'variant_id' => $variant->id,
                    'is_primary' => (bool) ($image->pivot->is_primary ?? false),
                ]);
            }
        }

        // Main image (from default variant)
        $mainImage = '/images/placeholder-product.jpg';
        if ($defaultVariant && $defaultVariant->images && $defaultVariant->images->isNotEmpty()) {
            $primaryImage = $defaultVariant->images->where('pivot.is_primary', true)->first();
            if (!$primaryImage) {
                $primaryImage = $defaultVariant->images->first();
            }
            if ($primaryImage) {
                $mainImage = $primaryImage->full_url ?? $primaryImage->thumb_url ?? $primaryImage->file_path;
            }
        }

        // Calculate discount
        $discountPercent = 0;
        if ($defaultVariant && $defaultVariant->compare_price && $defaultVariant->compare_price > $defaultVariant->price) {
            $discountPercent = round((($defaultVariant->compare_price - $defaultVariant->price) / $defaultVariant->compare_price) * 100);
        }

        // Get specifications
        $specifications = [];
        foreach ($product->specifications as $spec) {
            $value = null;
            if ($spec->pivot->custom_value) {
                $value = $spec->pivot->custom_value;
            } else if ($spec->pivot->specification_value_id) {
                $val = $spec->values->firstWhere('id', $spec->pivot->specification_value_id);
                $value = $val ? $val->value : null;
            }

            if ($value) {
                $specifications[] = [
                    'name' => $spec->name,
                    'value' => $value,
                ];
            }
        }

        // Process variants and group attributes
        $variants = $product->variants->map(function ($variant) {
            $images = $variant->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->full_url ?? $image->thumb_url ?? $image->file_path,
                    'is_primary' => (bool) ($image->pivot->is_primary ?? false),
                ];
            })->values()->toArray();

            $attributes = optional($variant->variantAttributes)
                ->map(function ($va) {
                    return [
                        'attribute_id'   => $va->attribute->id ?? null,
                        'attribute_name' => $va->attribute->name ?? null,
                        'attribute_code' => $va->attribute->code ?? null,
                        'attribute_type' => $va->attribute->type ?? null,
                        'value'          => $va->attributeValue->value ?? null,
                        'label'          => $va->attributeValue->label ?? null,
                        'color_code'     => $va->attributeValue->color_code ?? null,
                    ];
                })
                ->values()
                ->toArray();

            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => (float) $variant->price,
                'compare_price' => $variant->compare_price ? (float) $variant->compare_price : null,
                'cost_price' => $variant->cost_price ? (float) $variant->cost_price : null,
                'stock_quantity' => $variant->stock_quantity,
                'stock_status' => $variant->stock_status,
                'is_default' => (bool) $variant->is_default,
                'images' => $images,
                'attributes' => $attributes,
            ];
        })->toArray();

        // Group attributes by attribute type for easy display
        $attributeGroups = $this->groupAttributesByType($variants);

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'main_image' => $mainImage,
            'images' => $allImages->unique('id')->values()->toArray(),
            'price' => $defaultVariant ? (float) $defaultVariant->price : 0,
            'compare_price' => $defaultVariant ? (float) $defaultVariant->compare_price : null,
            'discount_percent' => $discountPercent,
            'is_in_stock' => $defaultVariant && $defaultVariant->stock_quantity > 0,
            'stock_quantity' => $defaultVariant ? $defaultVariant->stock_quantity : 0,
            'sku' => $defaultVariant ? $defaultVariant->sku : null,
            'default_variant_id' => $defaultVariant ? $defaultVariant->id : null,
            'brand' => $product->brand ? [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
                'description' => $product->brand->description,
            ] : null,
            'category' => $product->mainCategory ? [
                'id' => $product->mainCategory->id,
                'name' => $product->mainCategory->name,
                'slug' => $product->mainCategory->slug,
            ] : null,
            'categories' => $product->categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                ];
            })->toArray(),
            'is_featured' => (bool) $product->is_featured,
            'is_new' => (bool) $product->is_new,
            'is_bestseller' => (bool) $product->is_bestseller,
            'rating' => (float) ($product->rating ?? 4.5),
            'review_count' => (int) ($product->review_count ?? rand(10, 200)),
            'has_variants' => $product->product_type === 'configurable',
            'variants' => $variants,
            'attribute_groups' => $attributeGroups, // Add grouped attributes
            'specifications' => $specifications,
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'meta_keywords' => $product->meta_keywords,
            'created_at' => $product->created_at,
        ];
    }

    /**
     * Group attributes by attribute type
     */
    private function groupAttributesByType(array $variants): array
    {
        $attributeGroups = [];

        foreach ($variants as $variant) {
            foreach ($variant['attributes'] as $attribute) {
                $attributeName = $attribute['attribute_name'];
                $attributeValue = $attribute['value'];
                $attributeLabel = $attribute['label'] ?? $attribute['value'];
                $attributeType = $attribute['attribute_type'];

                // Initialize attribute group if not exists
                if (!isset($attributeGroups[$attributeName])) {
                    $attributeGroups[$attributeName] = [
                        'name' => $attributeName,
                        'type' => $attributeType,
                        'options' => []
                    ];
                }

                // Check if option already exists
                $optionExists = false;
                foreach ($attributeGroups[$attributeName]['options'] as $option) {
                    if ($option['value'] === $attributeValue) {
                        $optionExists = true;
                        // Add variant to existing option
                        $attributeGroups[$attributeName]['options'] = array_map(function ($opt) use ($attributeValue, $variant) {
                            if ($opt['value'] === $attributeValue) {
                                $opt['variants'][] = $variant['id'];
                                $opt['variant_ids'] = array_unique($opt['variants']);
                            }
                            return $opt;
                        }, $attributeGroups[$attributeName]['options']);
                        break;
                    }
                }

                // If option doesn't exist, add it
                if (!$optionExists) {
                    $attributeGroups[$attributeName]['options'][] = [
                        'value' => $attributeValue,
                        'label' => $attributeLabel,
                        'color_code' => $attribute['color_code'] ?? null,
                        'variants' => [$variant['id']],
                        'variant_ids' => [$variant['id']]
                    ];
                }
            }
        }

        // Sort options for each attribute group
        foreach ($attributeGroups as &$group) {
            // Sort options by label
            usort($group['options'], function ($a, $b) {
                return strcmp($a['label'], $b['label']);
            });
        }

        return $attributeGroups;
    }
    /**
     * Get related products
     */
    public function getRelatedProducts($productId, $limit = 4): array
    {
        try {
            $product = Product::find($productId);
            if (!$product) {
                return [];
            }

            $relatedProducts = Product::with([
                'variants' => function ($query) {
                    $query->where('is_default', true)
                        ->with('images');
                }
            ])
                ->where('status', 'active')
                ->where('id', '!=', $productId)
                ->where(function ($query) use ($product) {
                    $query->where('brand_id', $product->brand_id)
                        ->orWhere('main_category_id', $product->main_category_id)
                        ->orWhereHas('categories', function ($q) use ($product) {
                            $q->whereIn('categories.id', $product->categories->pluck('id'));
                        });
                })
                ->limit($limit)
                ->get();

            return $relatedProducts->map(function ($relatedProduct) {
                return $this->transformProductForListing($relatedProduct);
            })->toArray();

        } catch (\Exception $e) {
            Log::error('ProductService getRelatedProducts error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)
            ->where('status', 1)
            ->first();
    }

    /**
     * Get child categories
     */
    public function getChildCategories($categoryId)
    {
        return Category::where('parent_id', $categoryId)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'image_id']);
    }

    /**
     * Get related categories
     */
    public function getRelatedCategories($categoryId, $limit = 6)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return [];
        }

        return Category::where('parent_id', $category->parent_id)
            ->where('id', '!=', $categoryId)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'description', 'image_id']);
    }

    /**
     * Get category filters
     */
    public function getCategoryFilters($categoryId): array
    {
        $cacheKey = 'category_filters_' . $categoryId;

        return Cache::remember($cacheKey, 3600, function () use ($categoryId) {
            $filters = [];

            // Price range for category
            $priceRange = DB::table('products')
                ->join('product_variants', function ($join) {
                    $join->on('products.id', '=', 'product_variants.product_id')
                        ->where('product_variants.is_default', true);
                })
                ->where('products.status', 'active')
                ->where('products.main_category_id', $categoryId)
                ->selectRaw('MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price')
                ->first();
            $filters['price_range'] = [
                'min' => (int)($priceRange->min_price ?? 0),
                'max' => (int)($priceRange->max_price ?? 50000),
            ];

            // Brands in category
            $filters['brands'] = Brand::whereHas('products', function ($q) use ($categoryId) {
                $q->where('products.main_category_id', $categoryId)
                    ->where('products.status', 'active');
            })
                ->where('status', 1)
                ->select('id', 'name')
                ->withCount([
                    'products' => function ($q) use ($categoryId) {
                        $q->where('products.main_category_id', $categoryId)
                            ->where('products.status', 'active');
                    }
                ])
                ->orderBy('name')
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'count' => $brand->products_count
                    ];
                })
                ->toArray();

            return $filters;
        });
    }

    /**
     * Search products
     */
    public function searchProducts($searchQuery, $filters = [], $perPage = 12, $page = 1): LengthAwarePaginator
    {
        $filters['search'] = $searchQuery;
        return $this->getProducts($filters, $perPage, $page);
    }
}
