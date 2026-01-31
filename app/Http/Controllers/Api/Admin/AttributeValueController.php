<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AttributeValueRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeValueController extends Controller
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
     * Display attribute values for a specific attribute.
     */
    public function index(Request $request, $attributeId): JsonResponse
    {
        try {
            $attribute = Attribute::find($attributeId);

            if (!$attribute) {
                return $this->apiResponse(false, null, 'Attribute not found', 404);
            }

            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortDir = $request->get('sort_dir', 'asc');
            $status = $request->get('status');

            $query = $attribute->values();

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('value', 'LIKE', "%{$search}%")
                      ->orWhere('label', 'LIKE', "%{$search}%");
                });
            }

            if ($status !== null) {
                $query->where('status', (int) $status);
            }

            $query->orderBy($sortBy, $sortDir);
            $values = $query->paginate($perPage);

            $transformedData = $values->getCollection()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'value' => $value->value,
                    'label' => $value->label,
                    'color_code' => $value->color_code,
                    'image_id' => $value->image_id,
                    'sort_order' => $value->sort_order,
                    'status' => (bool) $value->status,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $value->created_at->format('M d, Y'),
                    'updated_at' => $value->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'attribute' => [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'code' => $attribute->code,
                    'type' => $attribute->type,
                ],
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $values->currentPage(),
                    'per_page' => $values->perPage(),
                    'total' => $values->total(),
                    'last_page' => $values->lastPage(),
                ]
            ], 'Attribute values retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute values index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attribute values', 500);
        }
    }

    /**
     * Store a new attribute value.
     */
    public function store(AttributeValueRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $attributeValue = AttributeValue::create($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $attributeValue->id,
                'value' => $attributeValue->value,
                'label' => $attributeValue->label,
                'attribute_id' => $attributeValue->attribute_id,
                'status' => (bool) $attributeValue->status,
            ], 'Attribute value created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Attribute value store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create attribute value', 500);
        }
    }

    /**
     * Display the specified attribute value.
     */
    public function show($id): JsonResponse
    {
        try {
            $attributeValue = AttributeValue::with('attribute')->find($id);

            if (!$attributeValue) {
                return $this->apiResponse(false, null, 'Attribute value not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $attributeValue->id,
                'attribute_id' => $attributeValue->attribute_id,
                'attribute_name' => $attributeValue->attribute->name,
                'value' => $attributeValue->value,
                'label' => $attributeValue->label,
                'color_code' => $attributeValue->color_code,
                'image_id' => $attributeValue->image_id,
                'sort_order' => $attributeValue->sort_order,
                'status' => (bool) $attributeValue->status,
                'created_at' => $attributeValue->created_at,
                'updated_at' => $attributeValue->updated_at,
            ], 'Attribute value retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute value show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve attribute value', 500);
        }
    }

    /**
     * Update the specified attribute value.
     */
    public function update(AttributeValueRequest $request, $id): JsonResponse
    {
        try {
            $attributeValue = AttributeValue::find($id);

            if (!$attributeValue) {
                return $this->apiResponse(false, null, 'Attribute value not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();

            $attributeValue->update($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $attributeValue->id,
                'value' => $attributeValue->value,
                'label' => $attributeValue->label,
                'attribute_id' => $attributeValue->attribute_id,
                'status' => (bool) $attributeValue->status,
            ], 'Attribute value updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Attribute value update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attribute value', 500);
        }
    }

    /**
     * Remove the specified attribute value.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $attributeValue = AttributeValue::find($id);

            if (!$attributeValue) {
                return $this->apiResponse(false, null, 'Attribute value not found', 404);
            }

            // Check if value is used in variant attributes
            if ($attributeValue->variantAttributes()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete attribute value. It is used in product variants.', 400);
            }

            $attributeValue->delete();

            return $this->apiResponse(true, null, 'Attribute value deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute value delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete attribute value', 500);
        }
    }

    /**
     * Toggle attribute value status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $attributeValue = AttributeValue::find($id);

            if (!$attributeValue) {
                return $this->apiResponse(false, null, 'Attribute value not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $attributeValue->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $attributeValue->id,
                'status' => (bool) $attributeValue->status,
            ], 'Attribute value status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Attribute value status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attribute value status', 500);
        }
    }

    /**
     * Bulk update attribute values.
     */
    public function bulkUpdate(Request $request, $attributeId): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:attribute_values,id',
                'field' => 'required|in:status',
                'value' => 'required'
            ]);

            // Verify all values belong to the same attribute
            $invalidValues = AttributeValue::whereIn('id', $request->ids)
                ->where('attribute_id', '!=', $attributeId)
                ->count();

            if ($invalidValues > 0) {
                return $this->apiResponse(false, null, 'Some values do not belong to this attribute', 400);
            }

            $field = $request->field;
            $value = $request->value;

            // Convert value to proper format
            if ($field === 'status') {
                if (is_bool($value) || $value === 'true' || $value === 'false') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                } else {
                    $value = (int) $value;
                }
            }

            $updated = AttributeValue::whereIn('id', $request->ids)
                ->update([$field => $value]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} attribute value(s) updated successfully");

        } catch (\Exception $e) {
            \Log::error('Attribute value bulk update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update attribute values', 500);
        }
    }

    /**
     * Bulk delete attribute values.
     */
    public function bulkDelete(Request $request, $attributeId): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:attribute_values,id',
            ]);

            // Verify all values belong to the same attribute
            $invalidValues = AttributeValue::whereIn('id', $request->ids)
                ->where('attribute_id', '!=', $attributeId)
                ->count();

            if ($invalidValues > 0) {
                return $this->apiResponse(false, null, 'Some values do not belong to this attribute', 400);
            }

            // Check if any value is used in variant attributes
            $valuesInUse = AttributeValue::whereIn('id', $request->ids)
                ->has('variantAttributes')
                ->count();

            if ($valuesInUse > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$valuesInUse} value(s) that are used in product variants", 400);
            }

            $deleted = AttributeValue::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} attribute value(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Attribute value bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete attribute values', 500);
        }
    }
}
