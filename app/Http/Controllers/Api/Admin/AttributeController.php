<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
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
     * Display a listing of attributes.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');
            $status = $request->get('status');
            $type = $request->get('type');
            $isVariant = $request->get('is_variant');
            $isFilterable = $request->get('is_filterable');

            $query = Attribute::withCount('values');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%");
                });
            }

            if ($status !== null) {
                $query->where('status', (int) $status);
            }

            if ($type) {
                $query->where('type', $type);
            }

            if ($isVariant !== null) {
                $query->where('is_variant', (int) $isVariant);
            }

            if ($isFilterable !== null) {
                $query->where('is_filterable', (int) $isFilterable);
            }

            $query->orderBy($sortBy, $sortDir);
            $attributes = $query->paginate($perPage);

            $transformedData = $attributes->getCollection()->map(function ($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'code' => $attribute->code,
                    'type' => $attribute->type,
                    'type_label' => ucfirst($attribute->type),
                    'is_variant' => (bool) $attribute->is_variant,
                    'is_filterable' => (bool) $attribute->is_filterable,
                    'sort_order' => $attribute->sort_order,
                    'status' => (bool) $attribute->status,
                    'values_count' => $attribute->values_count,
                    'created_at' => $attribute->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $attribute->created_at->format('M d, Y'),
                    'updated_at' => $attribute->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $attributes->currentPage(),
                    'per_page' => $attributes->perPage(),
                    'total' => $attributes->total(),
                    'last_page' => $attributes->lastPage(),
                ]
            ], 'Attributes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attributes', 500);
        }
    }

    /**
     * Store a newly created attribute.
     */
    public function store(AttributeRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $attribute = Attribute::create($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'code' => $attribute->code,
                'type' => $attribute->type,
                'status' => (bool) $attribute->status,
            ], 'Attribute created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Attribute store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create attribute', 500);
        }
    }

    /**
     * Display the specified attribute with its values.
     */
    public function show($id): JsonResponse
    {
        try {
            $attribute = Attribute::with(['values' => function($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }])->withCount('values')->find($id);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'code' => $attribute->code,
                'type' => $attribute->type,
                'type_label' => ucfirst($attribute->type),
                'is_variant' => (bool) $attribute->is_variant,
                'is_filterable' => (bool) $attribute->is_filterable,
                'sort_order' => $attribute->sort_order,
                'status' => (bool) $attribute->status,
                'values_count' => $attribute->values_count,
                'values' => $attribute->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                        'label' => $value->label,
                        'color_code' => $value->color_code,
                        'image_id' => $value->image_id,
                        'sort_order' => $value->sort_order,
                        'status' => (bool) $value->status,
                        'created_at' => $value->created_at,
                    ];
                }),
                'created_at' => $attribute->created_at,
                'updated_at' => $attribute->updated_at,
            ], 'Attribute retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attribute', 500);
        }
    }

    /**
     * Update the specified attribute.
     */
    public function update(AttributeRequest $request, $id): JsonResponse
    {
        try {
            $attribute = Attribute::find($id);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();

            $attribute->update($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'code' => $attribute->code,
                'type' => $attribute->type,
                'status' => (bool) $attribute->status,
            ], 'Attribute updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Attribute update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attribute', 500);
        }
    }

    /**
     * Remove the specified attribute.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $attribute = Attribute::find($id);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            // Check if attribute has values
            if ($attribute->values()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete attribute. It has associated values.', 400);
            }

            // Check if attribute is used in categories
            if ($attribute->categories()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete attribute. It is assigned to categories.', 400);
            }

            $attribute->delete();

            return $this->apiResponse(true, null, 'Attribute deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete attribute', 500);
        }
    }

    /**
     * Get attribute statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Attribute::count();
            $active = Attribute::where('status', 1)->count();
            $variantAttributes = Attribute::where('is_variant', 1)->where('status', 1)->count();
            $filterableAttributes = Attribute::where('is_filterable', 1)->where('status', 1)->count();

            // Get attribute with most values
            $popularAttribute = Attribute::withCount('values')
                ->where('status', 1)
                ->orderBy('values_count', 'desc')
                ->first();

            // Count by type
            $types = Attribute::select('type', DB::raw('COUNT(*) as count'))
                ->where('status', 1)
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type');

            return $this->apiResponse(true, [
                'total_attributes' => $total,
                'active_attributes' => $active,
                'variant_attributes' => $variantAttributes,
                'filterable_attributes' => $filterableAttributes,
                'popular_attribute' => $popularAttribute ? [
                    'id' => $popularAttribute->id,
                    'name' => $popularAttribute->name,
                    'code' => $popularAttribute->code,
                    'values_count' => $popularAttribute->values_count,
                ] : null,
                'types_count' => $types,
            ], 'Attribute statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Get available attribute types.
     */
    public function types(): JsonResponse
    {
        try {
            $types = [
                ['value' => 'select', 'label' => 'Select', 'description' => 'Single select dropdown'],
                ['value' => 'color', 'label' => 'Color', 'description' => 'Color swatch selector'],
                ['value' => 'image', 'label' => 'Image', 'description' => 'Image selector'],
                ['value' => 'text', 'label' => 'Text', 'description' => 'Text input field'],
            ];

            return $this->apiResponse(true, $types, 'Attribute types retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute types error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attribute types', 500);
        }
    }

    /**
     * Toggle attribute status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $attribute = Attribute::find($id);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $attribute->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $attribute->id,
                'status' => (bool) $attribute->status,
            ], 'Attribute status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attribute status', 500);
        }
    }

    /**
     * Toggle variant status.
     */
    public function toggleVariant(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'is_variant' => 'required|boolean'
            ]);

            $attribute = Attribute::find($id);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            $attribute->update(['is_variant' => (int) $request->is_variant]);

            return $this->apiResponse(true, [
                'id' => $attribute->id,
                'is_variant' => (bool) $attribute->is_variant,
            ], 'Attribute variant status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute variant update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update variant status', 500);
        }
    }

    /**
     * Toggle filterable status.
     */
    public function toggleFilterable(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'is_filterable' => 'required|boolean'
            ]);

            $attribute = Attribute::find($id);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            $attribute->update(['is_filterable' => (int) $request->is_filterable]);

            return $this->apiResponse(true, [
                'id' => $attribute->id,
                'is_filterable' => (bool) $attribute->is_filterable,
            ], 'Attribute filterable status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute filterable update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update filterable status', 500);
        }
    }

    /**
     * Bulk update attributes.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:attributes,id',
                'field' => 'required|in:status,is_variant,is_filterable',
                'value' => 'required'
            ]);

            $field = $request->field;
            $value = $request->value;

            // Convert value to proper format
            if ($field === 'status') {
                if (is_bool($value) || $value === 'true' || $value === 'false') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                } else {
                    $value = (int) $value;
                }
            } else {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }

            $updated = Attribute::whereIn('id', $request->ids)
                ->update([$field => $value]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} attribute(s) updated successfully");

        } catch (\Exception $e) {
            \Log::error('Attribute bulk update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attributes', 500);
        }
    }

    /**
     * Bulk delete attributes.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:attributes,id',
            ]);

            // Check if any attribute has values
            $attributesWithValues = Attribute::whereIn('id', $request->ids)
                ->has('values')
                ->count();

            if ($attributesWithValues > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$attributesWithValues} attribute(s) that have associated values", 400);
            }

            // Check if any attribute is used in categories
            $attributesInCategories = Attribute::whereIn('id', $request->ids)
                ->has('categories')
                ->count();

            if ($attributesInCategories > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$attributesInCategories} attribute(s) that are assigned to categories", 400);
            }

            $deleted = Attribute::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} attribute(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Attribute bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete attributes', 500);
        }
    }

    /**
     * Get attributes for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        try {
            $attributes = Attribute::select('id', 'name', 'code', 'type', 'is_variant')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function ($attribute) {
                    return [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'code' => $attribute->code,
                        'type' => $attribute->type,
                        'is_variant' => (bool) $attribute->is_variant,
                    ];
                });

            return $this->apiResponse(true, $attributes, 'Attributes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attributes', 500);
        }
    }

    /**
     * Get attributes with values for product variant creation.
     */
    public function forProductVariants(): JsonResponse
    {
        try {
            $attributes = Attribute::with(['values' => function($query) {
                $query->where('status', 1)
                      ->orderBy('sort_order')
                      ->orderBy('label');
            }])
            ->where('status', 1)
            ->where('is_variant', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'code' => $attribute->code,
                    'type' => $attribute->type,
                    'values' => $attribute->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                            'label' => $value->label,
                            'color_code' => $value->color_code,
                            'image_id' => $value->image_id,
                        ];
                    }),
                ];
            });

            return $this->apiResponse(true, $attributes, 'Variant attributes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Variant attributes error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve variant attributes', 500);
        }
    }
}
