<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Services\Admin\ProductService;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    private function apiResponse($success = true, $data = null, $message = '', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');
            $status = $request->get('status');
            $productType = $request->get('product_type');
            $categoryId = $request->get('category_id');
            $brandId = $request->get('brand_id');
            $stockStatus = $request->get('stock_status');
            $isFeatured = $request->get('is_featured');
            $isNew = $request->get('is_new');

            $query = Product::with(['brand:id,name', 'mainCategory:id,name', 'defaultVariant']);

            // Search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('slug', 'LIKE', "%{$search}%")
                        ->orWhere('product_code', 'LIKE', "%{$search}%")
                        ->orWhereHas('defaultVariant', function ($q) use ($search) {
                            $q->where('sku', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('brand', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('mainCategory', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                });
            }

            // Filters
            if ($status !== null) {
                $query->where('status', $status);
            }

            if ($productType) {
                $query->where('product_type', $productType);
            }

            if ($categoryId) {
                $query->where('main_category_id', $categoryId);
            }

            if ($brandId) {
                $query->where('brand_id', $brandId);
            }

            if ($isFeatured !== null) {
                $query->where('is_featured', filter_var($isFeatured, FILTER_VALIDATE_BOOLEAN));
            }

            if ($isNew !== null) {
                $query->where('is_new', filter_var($isNew, FILTER_VALIDATE_BOOLEAN));
            }

            // Stock status filter
            if ($stockStatus) {
                if ($stockStatus === 'in_stock') {
                    $query->whereHas('defaultVariant', function ($q) {
                        $q->where('stock_quantity', '>', 0);
                    });
                } elseif ($stockStatus === 'out_of_stock') {
                    $query->whereHas('defaultVariant', function ($q) {
                        $q->where('stock_quantity', '<=', 0);
                    });
                } elseif ($stockStatus === 'low_stock') {
                    $query->whereHas('defaultVariant', function ($q) {
                        $q->where('stock_quantity', '>', 0)
                            ->where('stock_quantity', '<=', 10);
                    });
                }
            }

            $query->orderBy($sortBy, $sortDir);
            $products = $query->paginate($perPage);

            $transformedData = $products->getCollection()->map(function ($product) {
                $defaultVariant = $product->defaultVariant;
                $totalStock = $product->variants()->sum('stock_quantity');

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'product_type' => $product->product_type,
                    'product_type_label' => $product->product_type === 'configurable' ? 'Configurable' : 'Simple',
                    'sku' => $defaultVariant ? $defaultVariant->sku : 'N/A',
                    'price' => $defaultVariant ? (float) $defaultVariant->price : 0,
                    'compare_price' => $defaultVariant ? (float) $defaultVariant->compare_price : null,
                    'cost_price' => $defaultVariant ? (float) $defaultVariant->cost_price : null,
                    'special_price' => $defaultVariant ? (float) $defaultVariant->compare_price : null,
                    'current_price' => $defaultVariant ? ($defaultVariant->compare_price ?: $defaultVariant->price) : 0,
                    'stock_quantity' => $defaultVariant ? $defaultVariant->stock_quantity : 0,
                    'total_stock' => $totalStock,
                    'stock_status' => $defaultVariant ? $defaultVariant->stock_status : 'out_of_stock',
                    'main_image' => $defaultVariant ? $this->getVariantImage($defaultVariant) : null,
                    'brand_id' => $product->brand_id,
                    'brand_name' => $product->brand ? $product->brand->name : null,
                    'main_category_id' => $product->main_category_id,
                    'main_category_name' => $product->mainCategory ? $product->mainCategory->name : null,
                    'status' => $product->status,
                    'is_featured' => (bool) $product->is_featured,
                    'is_new' => (bool) $product->is_new,
                    'is_bestseller' => (bool) $product->is_bestseller,
                    'weight' => $product->weight,
                    'dimensions' => [
                        'length' => $product->length,
                        'width' => $product->width,
                        'height' => $product->height,
                    ],
                    'has_variants' => $product->product_type === 'configurable',
                    'variants_count' => $product->variants()->count(),
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $product->created_at->format('M d, Y'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                ]
            ], 'Products retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Product index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve products', 500);
        }
    }

    /**
     * Store a newly created product.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        try {
            Log::info('Product store request received', ['data' => $request->all()]);

            $result = $this->productService->createProduct($request->validated());

            if ($result['success']) {
                return $this->apiResponse(true, [
                    'id' => $result['product']->id,
                    'name' => $result['product']->name,
                    'slug' => $result['product']->slug,
                    'product_type' => $result['product']->product_type,
                    'status' => $result['product']->status,
                ], 'Product created successfully', 201);
            } else {
                return $this->apiResponse(false, null, 'Failed to create product: ' . $result['error'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Product store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create product: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get specifications for a category (organized by groups)
     */
    public function getCategorySpecifications($categoryId): JsonResponse
    {
        try {
            $specifications = $this->productService->getCategorySpecifications($categoryId);
            return $this->apiResponse(true, $specifications, 'Category specifications retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Category specifications error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve category specifications: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get attributes for variants in a category
     */
    public function getCategoryAttributes($categoryId): JsonResponse
    {
        try {
            $attributes = $this->productService->getCategoryAttributes($categoryId);
            return $this->apiResponse(true, $attributes, 'Variant attributes retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Category attributes error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve category attributes: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate variants based on selected attributes
     */
    public function generateVariants(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'attributes' => 'required|array|min:1',
                'base_sku' => 'required|string|min:3',
                'base_price' => 'required|numeric|min:0',
            ]);

            $result = $this->productService->generateVariants($request->all());

            if ($result['success']) {
                return $this->apiResponse(true, $result, 'Variants generated successfully');
            } else {
                return $this->apiResponse(false, null, 'Failed to generate variants: ' . $result['error'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Generate variants error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to generate variants: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show($id): JsonResponse
    {
        try {
            $product = Product::with([
                'brand:id,name,slug',
                'mainCategory:id,name,slug',
                'taxClass:id,name',
                'categories:id,name,slug',
                'tags:id,name,color',
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
                        'images'

                    ]);
                }
            ])->find($id);

            if (!$product) {
                return $this->apiResponse(false, null, 'Product not found', 404);
            }

            $defaultVariant = $product->defaultVariant;

            return $this->apiResponse(true, [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'product_type' => $product->product_type,
                'product_type_label' => $product->product_type === 'configurable' ? 'Configurable' : 'Simple',
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name,
                    'slug' => $product->brand->slug,
                ] : null,
                'main_category' => $product->mainCategory ? [
                    'id' => $product->mainCategory->id,
                    'name' => $product->mainCategory->name,
                    'slug' => $product->mainCategory->slug,
                ] : null,
                'categories' => $product->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                    ];
                }),
                'tax_class' => $product->taxClass ? [
                    'id' => $product->taxClass->id,
                    'name' => $product->taxClass->name,
                    'rates' => $product->taxClass->rates->map(function ($rate) {
                        return [
                            'id' => $rate->id,
                            'rate' => (float) $rate->rate,
                            'country_code' => $rate->country_code,
                            'state_code' => $rate->state_code,
                            'priority' => $rate->priority,
                        ];
                    }),
                ] : null,

                'short_description' => $product->short_description,
                'description' => $product->description,
                'status' => $product->status,
                'is_featured' => (bool) $product->is_featured,
                'is_new' => (bool) $product->is_new,
                'is_bestseller' => (bool) $product->is_bestseller,
                'weight' => (float) $product->weight,
                'dimensions' => [
                    'length' => (float) $product->length,
                    'width' => (float) $product->width,
                    'height' => (float) $product->height,
                ],
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'meta_keywords' => $product->meta_keywords,
                'canonical_url' => $product->canonical_url,
                'product_code' => $product->product_code,
                'tags' => $product->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'color' => $tag->color,
                    ];
                }),
                'specifications' => $product->specifications->map(function ($spec) {

                    $value = null;

                    if (!empty($spec->pivot->custom_value)) {
                        $value = $spec->pivot->custom_value;
                    } elseif (!empty($spec->pivot->specification_value_id)) {
                        $selected = $spec->values
                            ->firstWhere('id', $spec->pivot->specification_value_id);

                        $value = $selected?->value;
                    }

                    return [
                        'specification_id' => $spec->id,
                        'specification_name' => $spec->name,
                        'specification_unit' => $spec->unit,
                        'value' => $value,
                        'display_value' => $value,
                    ];
                }),

                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'price' => (float) $variant->price,
                        'compare_price' => $variant->compare_price ? (float) $variant->compare_price : null,
                        'cost_price' => $variant->cost_price ? (float) $variant->cost_price : null,
                        'stock_quantity' => $variant->stock_quantity,
                        'reserved_quantity' => $variant->reserved_quantity,
                        'available_stock' => $variant->stock_quantity - $variant->reserved_quantity,
                        'stock_status' => $variant->stock_status,
                        'is_default' => (bool) $variant->is_default,
                        'status' => (bool) $variant->status,
                        'attributes' => $variant->attributes->map(function ($attr) {
                            return [
                                'attribute_id' => $attr->id,
                                'attribute_name' => $attr->name,
                                'attribute_value_id' => $attr->pivot->attribute_value_id,
                                'attribute_value' => $attr->value,
                                'label' => $attr->label,
                                'color_code' => $attr->color_code,
                            ];
                        }),
                        'combination_display' => $this->getCombinationDisplay($variant),
                        'images' => $variant->images->map(function ($image) {
                            return [
                                'id' => $image->id,
                                'url' => $image->full_url ?? $image->path,
                                'thumb_url' => $image->thumb_url,
                                'is_primary' => (bool) $image->pivot->is_primary,
                                'sort_order' => $image->pivot->sort_order,
                            ];
                        })->sortBy('sort_order')->values(),
                    ];
                }),
                'default_variant' => $defaultVariant ? [
                    'id' => $defaultVariant->id,
                    'sku' => $defaultVariant->sku,
                    'price' => (float) $defaultVariant->price,
                    'compare_price' => $defaultVariant->compare_price ? (float) $defaultVariant->compare_price : null,
                    'stock_quantity' => $defaultVariant->stock_quantity,
                    'stock_status' => $defaultVariant->stock_status,
                ] : null,
                'total_stock' => $product->variants()->sum('stock_quantity'),
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ], 'Product retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Product show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve product', 500);
        }
    }

    /**
     * Update the specified product.
     */
    // In ProductController, update the update method:

    /**
     * Update the specified product.
     */
    public function update(ProductRequest $request, $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->apiResponse(false, null, 'Product not found', 404);
            }

            $result = $this->productService->updateProduct($product, $request->validated());

            if ($result['success']) {
                return $this->apiResponse(true, [
                    'id' => $result['product']->id,
                    'name' => $result['product']->name,
                    'slug' => $result['product']->slug,
                    'product_type' => $result['product']->product_type,
                    'status' => $result['product']->status,
                ], 'Product updated successfully');
            } else {
                return $this->apiResponse(false, null, 'Failed to update product: ' . $result['error'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update product', 500);
        }
    }

    /**
     * Get product for edit form.
     */
    public function getForEdit($id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->apiResponse(false, null, 'Product not found', 404);
            }

            $data = $this->productService->getProductForEdit($product);

            return $this->apiResponse(true, $data, 'Product data retrieved for edit');

        } catch (\Exception $e) {
            Log::error('Get product for edit error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve product data for edit', 500);
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->apiResponse(false, null, 'Product not found', 404);
            }

            // Check if product has orders
            if ($product->orderItems()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete product. It has associated orders.', 400);
            }

            // Soft delete
            $product->delete();

            return $this->apiResponse(true, null, 'Product deleted successfully');

        } catch (\Exception $e) {
            Log::error('Product delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete product', 500);
        }
    }

    /**
     * Get product statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Product::count();
            $active = Product::where('status', 'active')->count();
            $draft = Product::where('status', 'draft')->count();
            $simple = Product::where('product_type', 'simple')->count();
            $configurable = Product::where('product_type', 'configurable')->count();
            $featured = Product::where('is_featured', true)->count();
            $new = Product::where('is_new', true)->count();
            $lowStock = Product::whereHas('variants', function ($query) {
                $query->where('stock_quantity', '>', 0)
                    ->where('stock_quantity', '<=', 10);
            })->count();
            $outOfStock = Product::whereHas('variants', function ($query) {
                $query->where('stock_quantity', '<=', 0);
            })->count();

            // Latest products
            $latestProducts = Product::with('defaultVariant')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->defaultVariant ? $product->defaultVariant->sku : 'N/A',
                        'price' => $product->defaultVariant ? $product->defaultVariant->price : 0,
                        'status' => $product->status,
                        'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            // Categories with product count
            $categories = \DB::table('categories')
                ->select('categories.id', 'categories.name', \DB::raw('COUNT(products.id) as product_count'))
                ->leftJoin('category_product', 'categories.id', '=', 'category_product.category_id')
                ->leftJoin('products', 'category_product.product_id', '=', 'products.id')
                ->whereNull('products.deleted_at')
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('product_count', 'desc')
                ->limit(5)
                ->get();

            return $this->apiResponse(true, [
                'total_products' => $total,
                'active_products' => $active,
                'draft_products' => $draft,
                'simple_products' => $simple,
                'configurable_products' => $configurable,
                'featured_products' => $featured,
                'new_products' => $new,
                'low_stock_products' => $lowStock,
                'out_of_stock_products' => $outOfStock,
                'latest_products' => $latestProducts,
                'top_categories' => $categories,
            ], 'Product statistics retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Product statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Check SKU availability.
     */
    public function checkSku(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'sku' => 'required|string',
                'exclude_id' => 'nullable|integer',
            ]);

            $query = \App\Models\ProductVariant::where('sku', $request->sku);

            if ($request->exclude_id) {
                $query->where('id', '!=', $request->exclude_id);
            }

            $exists = $query->exists();

            return $this->apiResponse(true, [
                'available' => !$exists,
                'sku' => $request->sku,
            ], 'SKU availability checked');

        } catch (\Exception $e) {
            Log::error('Check SKU error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to check SKU', 500);
        }
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:draft,pending,active,inactive'
            ]);

            $product = Product::find($id);

            if (!$product) {
                return $this->apiResponse(false, null, 'Product not found', 404);
            }

            $product->update(['status' => $request->status]);

            return $this->apiResponse(true, [
                'id' => $product->id,
                'status' => $product->status,
            ], 'Product status updated successfully');

        } catch (\Exception $e) {
            Log::error('Product status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update product status', 500);
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'is_featured' => 'required|boolean'
            ]);

            $product = Product::find($id);

            if (!$product) {
                return $this->apiResponse(false, null, 'Product not found', 404);
            }

            $product->update(['is_featured' => $request->is_featured]);

            return $this->apiResponse(true, [
                'id' => $product->id,
                'is_featured' => (bool) $product->is_featured,
            ], 'Product featured status updated successfully');

        } catch (\Exception $e) {
            Log::error('Product featured update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update featured status', 500);
        }
    }

    /**
     * Bulk update products.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:products,id',
                'field' => 'required|in:status,is_featured,is_new',
                'value' => 'required'
            ]);

            $field = $request->field;
            $value = $request->value;

            if ($field === 'status') {
                $validStatuses = ['draft', 'pending', 'active', 'inactive'];
                if (!in_array($value, $validStatuses)) {
                    return $this->apiResponse(false, null, 'Invalid status value', 400);
                }
            } else {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            $updated = Product::whereIn('id', $request->ids)
                ->update([$field => $value]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} product(s) updated successfully");

        } catch (\Exception $e) {
            Log::error('Product bulk update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update products', 500);
        }
    }

    /**
     * Bulk delete products.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:products,id',
            ]);

            // Check if any product has orders
            $productsWithOrders = Product::whereIn('id', $request->ids)
                ->has('orderItems')
                ->count();

            if ($productsWithOrders > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$productsWithOrders} product(s) that have associated orders", 400);
            }

            $deleted = Product::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} product(s) deleted successfully");

        } catch (\Exception $e) {
            Log::error('Product bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete products', 500);
        }
    }

    /**
     * Get products for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        try {
            $products = Product::select('id', 'name', 'product_type')
                ->where('status', 'active')
                ->with(['defaultVariant:id,product_id,sku,price'])
                ->orderBy('name')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'product_type' => $product->product_type,
                        'sku' => $product->defaultVariant ? $product->defaultVariant->sku : 'N/A',
                        'price' => $product->defaultVariant ? (float) $product->defaultVariant->price : 0,
                    ];
                });

            return $this->apiResponse(true, $products, 'Products retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Product dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve products', 500);
        }
    }

    /**
     * Helper: Get variant image URL.
     */
    private function getVariantImage(?ProductVariant $variant): ?string
    {
        if (!$variant) {
            return null;
        }

        $image = $variant->images()->where('variant_images.is_primary', true)->first();

        if ($image) {
            return $image->full_url ?? $image->path;
        }


        $firstImage = $variant->images()->first();

        if ($firstImage) {
            return $firstImage->full_url ?? $firstImage->path;
        }


        return null;
    }

    /**
     * Helper: Get combination display string.
     */
    private function getCombinationDisplay(ProductVariant $variant): string
    {
        $attributes = $variant->attributes;
        if ($attributes->isEmpty()) {
            return 'Default';
        }

        $display = [];
        foreach ($attributes as $attribute) {
            $display[] = $attribute->name . ': ' . $attribute->value;
        }

        return implode(' | ', $display);
    }
}
