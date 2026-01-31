<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\SpecificationValueRequest;
use App\Models\Specification;
use App\Models\SpecificationValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecificationValueController extends Controller
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
     * Display specification values for a specific specification.
     */
    public function index(Request $request, $specificationId): JsonResponse
    {
        try {
            $specification = Specification::find($specificationId);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortDir = $request->get('sort_dir', 'asc');
            $status = $request->get('status');

            $query = $specification->values();

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('value', 'LIKE', "%{$search}%");
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
                    'sort_order' => $value->sort_order,
                    'status' => (bool) $value->status,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $value->created_at->format('M d, Y'),
                    'updated_at' => $value->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'specification' => [
                    'id' => $specification->id,
                    'name' => $specification->name,
                    'code' => $specification->code,
                    'input_type' => $specification->input_type,
                ],
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $values->currentPage(),
                    'per_page' => $values->perPage(),
                    'total' => $values->total(),
                    'last_page' => $values->lastPage(),
                ]
            ], 'Specification values retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification values index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification values', 500);
        }
    }

    /**
     * Store a new specification value.
     */
    public function store(SpecificationValueRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $specificationValue = SpecificationValue::create($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $specificationValue->id,
                'value' => $specificationValue->value,
                'specification_id' => $specificationValue->specification_id,
                'status' => (bool) $specificationValue->status,
            ], 'Specification value created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Specification value store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create specification value', 500);
        }
    }

    /**
     * Display the specified specification value.
     */
    public function show($id): JsonResponse
    {
        try {
            $specificationValue = SpecificationValue::with('specification')->find($id);

            if (!$specificationValue) {
                return $this->apiResponse(false, null, 'Specification value not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $specificationValue->id,
                'specification_id' => $specificationValue->specification_id,
                'specification_name' => $specificationValue->specification->name,
                'value' => $specificationValue->value,
                'sort_order' => $specificationValue->sort_order,
                'status' => (bool) $specificationValue->status,
                'created_at' => $specificationValue->created_at,
                'updated_at' => $specificationValue->updated_at,
            ], 'Specification value retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification value show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification value', 500);
        }
    }

    /**
     * Update the specified specification value.
     */
    public function update(SpecificationValueRequest $request, $id): JsonResponse
    {
        try {
            $specificationValue = SpecificationValue::find($id);

            if (!$specificationValue) {
                return $this->apiResponse(false, null, 'Specification value not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();

            $specificationValue->update($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $specificationValue->id,
                'value' => $specificationValue->value,
                'specification_id' => $specificationValue->specification_id,
                'status' => (bool) $specificationValue->status,
            ], 'Specification value updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Specification value update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification value', 500);
        }
    }

    /**
     * Remove the specified specification value.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $specificationValue = SpecificationValue::find($id);

            if (!$specificationValue) {
                return $this->apiResponse(false, null, 'Specification value not found', 404);
            }

            // Check if value is used in product specifications
            if ($specificationValue->productSpecifications()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete specification value. It is used in products.', 400);
            }

            $specificationValue->delete();

            return $this->apiResponse(true, null, 'Specification value deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Specification value delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete specification value', 500);
        }
    }

    /**
     * Toggle specification value status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $specificationValue = SpecificationValue::find($id);

            if (!$specificationValue) {
                return $this->apiResponse(false, null, 'Specification value not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $specificationValue->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $specificationValue->id,
                'status' => (bool) $specificationValue->status,
            ], 'Specification value status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Specification value status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification value status', 500);
        }
    }

    /**
     * Bulk update specification values.
     */
    public function bulkUpdate(Request $request, $specificationId): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:specification_values,id',
                'field' => 'required|in:status',
                'value' => 'required'
            ]);

            // Verify all values belong to the same specification
            $invalidValues = SpecificationValue::whereIn('id', $request->ids)
                ->where('specification_id', '!=', $specificationId)
                ->count();

            if ($invalidValues > 0) {
                return $this->apiResponse(false, null, 'Some values do not belong to this specification', 400);
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

            $updated = SpecificationValue::whereIn('id', $request->ids)
                ->update([$field => $value]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} specification value(s) updated successfully");

        } catch (\Exception $e) {
            \Log::error('Specification value bulk update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification values', 500);
        }
    }

    /**
     * Bulk delete specification values.
     */
    public function bulkDelete(Request $request, $specificationId): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:specification_values,id',
            ]);

            // Verify all values belong to the same specification
            $invalidValues = SpecificationValue::whereIn('id', $request->ids)
                ->where('specification_id', '!=', $specificationId)
                ->count();

            if ($invalidValues > 0) {
                return $this->apiResponse(false, null, 'Some values do not belong to this specification', 400);
            }

            // Check if any value is used in product specifications
            $valuesInUse = SpecificationValue::whereIn('id', $request->ids)
                ->has('productSpecifications')
                ->count();

            if ($valuesInUse > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$valuesInUse} value(s) that are used in products", 400);
            }

            $deleted = SpecificationValue::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} specification value(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Specification value bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete specification values', 500);
        }
    }
}
