<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\CategoryRequest;
use App\Models\Category;
use App\Models\Media;
use App\Models\SpecificationGroup;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private function apiResponse($success = true, $data = null, $message = '', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Display a listing of categories.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortDir = $request->get('sort_dir', 'asc');
            $status = $request->get('status');
            $parentId = $request->get('parent_id');

            $query = Category::with(['parent', 'image'])
                ->withCount(['products', 'children']);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%");
                });
            }

            if ($status !== null) {
                $query->where('status', (int) $status);
            }

            if ($parentId !== null) {
                $query->where('parent_id', $parentId);
            }

            $query->orderBy($sortBy, $sortDir);
            $categories = $query->paginate($perPage);

            $transformedData = $categories->getCollection()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'status' => (bool) $category->status,
                    'featured' => (bool) $category->featured,
                    'show_in_nav' => (bool) $category->show_in_nav,
                    'sort_order' => $category->sort_order,
                    'image_id' => $category->image_id,
                    'image' => $category->image ? $category->image->url : null,
                    'parent_id' => $category->parent_id,
                    'parent_name' => $category->parent ? $category->parent->name : null,
                    'products_count' => $category->products_count,
                    'children_count' => $category->children_count,
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
                ];
            })->values()->all();

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'last_page' => $categories->lastPage(),
                ]
            ], 'Categories retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve categories', 500);
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $specGroupIds = $data['spec_group_ids'] ?? [];
            $attributes = $data['attributes'] ?? [];
            unset($data['spec_group_ids'], $data['attributes']);

            // Create category
            $category = Category::create($data);

            // Attach specification groups with correct column name (spec_group_id, not specification_group_id)
            if (!empty($specGroupIds)) {
                foreach ($specGroupIds as $specGroupId) {
                    DB::table('category_spec_groups')->insert([
                        'category_id' => $category->id,
                        'spec_group_id' => $specGroupId, // CORRECT COLUMN NAME
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Attach attributes
            if (!empty($attributes)) {
                foreach ($attributes as $attributeId => $attributeData) {
                    $category->attributes()->attach($attributeId, [
                        'is_required' => $attributeData['is_required'] ?? false,
                        'is_filterable' => $attributeData['is_filterable'] ?? false,
                        'sort_order' => $attributeData['sort_order'] ?? 0,
                    ]);
                }
            }

            // Update category hierarchy
            $this->updateCategoryHierarchy($category);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ], 'Category created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Category store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create category', 500);
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::with([
                'parent',
                'image',
                'children',
                'specificationGroups',
                'attributes' => function($query) {
                    $query->withPivot('is_required', 'is_filterable', 'sort_order');
                }
            ])->withCount(['products', 'children'])->find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            // Get specification group IDs
            $specGroupIds = $category->specificationGroups->pluck('id')->toArray();

            // Get category attributes with pivot data
            $categoryAttributes = $category->attributes->map(function ($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'code' => $attribute->code,
                    'pivot' => [
                        'is_required' => (bool) $attribute->pivot->is_required,
                        'is_filterable' => (bool) $attribute->pivot->is_filterable,
                        'sort_order' => $attribute->pivot->sort_order,
                    ]
                ];
            });

            return $this->apiResponse(true, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'status' => (bool) $category->status,
                'featured' => (bool) $category->featured,
                'show_in_nav' => (bool) $category->show_in_nav,
                'sort_order' => $category->sort_order,
                'image_id' => $category->image_id,
                'image' => $category->image ? $category->image->url : null,
                'parent_id' => $category->parent_id,
                'parent_name' => $category->parent ? $category->parent->name : null,
                'products_count' => $category->products_count,
                'children_count' => $category->children_count,
                'spec_group_ids' => $specGroupIds,
                'attributes' => $categoryAttributes,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ], 'Category retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve category', 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(CategoryRequest $request, $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();
            $specGroupIds = $data['spec_group_ids'] ?? [];
            $attributes = $data['attributes'] ?? [];
            unset($data['spec_group_ids'], $data['attributes']);

            // Update category
            $category->update($data);

            // Sync specification groups with correct column name
            if (isset($request->spec_group_ids)) {
                // Delete existing specification groups
                DB::table('category_spec_groups')->where('category_id', $id)->delete();

                // Insert new specification groups
                if (!empty($specGroupIds)) {
                    foreach ($specGroupIds as $specGroupId) {
                        DB::table('category_spec_groups')->insert([
                            'category_id' => $category->id,
                            'spec_group_id' => $specGroupId, // CORRECT COLUMN NAME
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Sync attributes
            if (isset($request->attributes)) {
                $category->attributes()->detach();
                if (!empty($attributes)) {
                    foreach ($attributes as $attributeId => $attributeData) {
                        $category->attributes()->attach($attributeId, [
                            'is_required' => $attributeData['is_required'] ?? false,
                            'is_filterable' => $attributeData['is_filterable'] ?? false,
                            'sort_order' => $attributeData['sort_order'] ?? 0,
                        ]);
                    }
                }
            }

            // Update category hierarchy
            $this->updateCategoryHierarchy($category);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ], 'Category updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Category update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update category', 500);
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            // Check if category has products
            if ($category->products()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete category with products', 400);
            }

            DB::beginTransaction();

            // Detach all relationships
            DB::table('category_spec_groups')->where('category_id', $id)->delete();
            $category->attributes()->detach();

            // Remove from category hierarchy
            DB::table('category_hierarchies')->where('ancestor_id', $id)->orWhere('descendant_id', $id)->delete();

            // Set children's parent_id to null
            Category::where('parent_id', $id)->update(['parent_id' => null]);

            // Delete category
            $category->delete();

            DB::commit();

            return $this->apiResponse(true, null, 'Category deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Category delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete category', 500);
        }
    }

    /**
     * Get category statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Category::count();
            $active = Category::where('status', 1)->count();
            $mainCategories = Category::whereNull('parent_id')->count();

            // Get category with most products
            $popularCategory = Category::withCount('products')
                ->where('status', 1)
                ->orderBy('products_count', 'desc')
                ->first();

            return $this->apiResponse(true, [
                'total_categories' => $total,
                'active_categories' => $active,
                'main_categories' => $mainCategories,
                'popular_category' => $popularCategory ? [
                    'id' => $popularCategory->id,
                    'name' => $popularCategory->name,
                    'products_count' => $popularCategory->products_count,
                ] : null,
            ], 'Category statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Get categories for dropdown.
     */
    public function dropdown(Request $request): JsonResponse
    {
        try {
            $excludeId = $request->get('exclude_id');

            $query = Category::select('id', 'name', 'parent_id', 'slug')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->orderBy('name');

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $categories = $query->get();

            // Build tree structure
            $tree = $this->buildCategoryTree($categories);

            return $this->apiResponse(true, $tree, 'Categories retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve categories', 500);
        }
    }

    /**
     * Get category tree.
     */
    public function tree(): JsonResponse
    {
        try {
            $categories = Category::select('id', 'name', 'parent_id', 'slug')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $tree = $this->buildCategoryTree($categories);

            return $this->apiResponse(true, $tree, 'Category tree retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category tree error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve category tree', 500);
        }
    }

    /**
     * Update category status.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $category->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $category->id,
                'status' => (bool) $category->status,
            ], 'Category status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Category status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update category status', 500);
        }
    }

    /**
     * Bulk update category status.
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:categories,id',
                'status' => 'required|in:0,1,true,false'
            ]);

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $updated = Category::whereIn('id', $request->ids)
                ->update(['status' => $status]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} category(s) updated successfully");

        } catch (\Exception $e) {
            \Log::error('Category bulk status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update categories', 500);
        }
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:categories,id',
            ]);

            // Check if any category has products
            $categoriesWithProducts = Category::whereIn('id', $request->ids)
                ->has('products')
                ->count();

            if ($categoriesWithProducts > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$categoriesWithProducts} category(s) that have products", 400);
            }

            DB::beginTransaction();

            foreach ($request->ids as $id) {
                $category = Category::find($id);
                if ($category) {
                    // Detach relationships
                    DB::table('category_spec_groups')->where('category_id', $id)->delete();
                    $category->attributes()->detach();

                    // Remove from hierarchy
                    DB::table('category_hierarchies')->where('ancestor_id', $id)->orWhere('descendant_id', $id)->delete();

                    // Set children's parent_id to null
                    Category::where('parent_id', $id)->update(['parent_id' => null]);

                    // Delete category
                    $category->delete();
                }
            }

            DB::commit();

            return $this->apiResponse(true, [
                'deleted_count' => count($request->ids),
            ], count($request->ids) . " category(s) deleted successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Category bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete categories', 500);
        }
    }

    /**
     * Get specification groups for a category.
     */
    public function getSpecGroups($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            $specGroups = $category->specificationGroups()
                ->select('specification_groups.id', 'specification_groups.name')
                ->get();

            return $this->apiResponse(true, $specGroups, 'Specification groups retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category spec groups error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification groups', 500);
        }
    }

    /**
     * Update specification groups for a category.
     */
    public function updateSpecGroups(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'spec_group_ids' => 'required|array',
                'spec_group_ids.*' => 'exists:specification_groups,id'
            ]);

            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            DB::beginTransaction();

            // Delete existing
            DB::table('category_spec_groups')->where('category_id', $id)->delete();

            // Insert new with correct column name
            foreach ($request->spec_group_ids as $specGroupId) {
                DB::table('category_spec_groups')->insert([
                    'category_id' => $category->id,
                    'spec_group_id' => $specGroupId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return $this->apiResponse(true, null, 'Specification groups updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Category update spec groups error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification groups', 500);
        }
    }

    /**
     * Get attributes for a category.
     */
    public function getAttributes($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            $attributes = $category->attributes()
                ->withPivot('is_required', 'is_filterable', 'sort_order')
                ->get();

            return $this->apiResponse(true, $attributes, 'Attributes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category attributes error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attributes', 500);
        }
    }

    /**
     * Update attributes for a category.
     */
    public function updateAttributes(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'attributes' => 'required|array',
                'attributes.*.is_required' => 'boolean',
                'attributes.*.is_filterable' => 'boolean',
                'attributes.*.sort_order' => 'integer|min:0'
            ]);

            $category = Category::find($id);

            if (!$category) {
                return $this->apiResponse(false, null, 'Category not found', 404);
            }

            $attributes = [];
            foreach ($request->attributes as $attributeId => $attributeData) {
                $attributes[$attributeId] = [
                    'is_required' => $attributeData['is_required'] ?? false,
                    'is_filterable' => $attributeData['is_filterable'] ?? false,
                    'sort_order' => $attributeData['sort_order'] ?? 0,
                ];
            }

            $category->attributes()->sync($attributes);

            return $this->apiResponse(true, null, 'Attributes updated successfully');

        } catch (\Exception $e) {
            \Log::error('Category update attributes error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attributes', 500);
        }
    }

    /**
     * Build category tree recursively.
     */
    private function buildCategoryTree($categories, $parentId = null)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildCategoryTree($categories, $category->id);
                $node = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent_id' => $category->parent_id,
                ];

                if (!empty($children)) {
                    $node['children'] = $children;
                }

                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * Update category hierarchy.
     */
    private function updateCategoryHierarchy(Category $category)
    {
        // Clear existing hierarchy for this category
        DB::table('category_hierarchies')
            ->where('descendant_id', $category->id)
            ->delete();

        // Add self as descendant
        DB::table('category_hierarchies')->insert([
            'ancestor_id' => $category->id,
            'descendant_id' => $category->id,
            'depth' => 0,
        ]);

        // If category has parent, add parent's ancestors
        if ($category->parent_id) {
            $parentAncestors = DB::table('category_hierarchies')
                ->where('descendant_id', $category->parent_id)
                ->get();

            foreach ($parentAncestors as $ancestor) {
                DB::table('category_hierarchies')->insert([
                    'ancestor_id' => $ancestor->ancestor_id,
                    'descendant_id' => $category->id,
                    'depth' => $ancestor->depth + 1,
                ]);
            }
        }
    }
}
