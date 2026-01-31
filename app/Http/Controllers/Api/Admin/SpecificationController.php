<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\SpecificationRequest;
use App\Models\Specification;
use App\Models\SpecificationGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecificationController extends Controller
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
     * Display a listing of specifications.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');
            $status = $request->get('status');
            $inputType = $request->get('input_type');
            $isRequired = $request->get('is_required');
            $isFilterable = $request->get('is_filterable');

            $query = Specification::withCount('values');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%");
                });
            }

            if ($status !== null) {
                $query->where('status', (int) $status);
            }

            if ($inputType) {
                $query->where('input_type', $inputType);
            }

            if ($isRequired !== null) {
                $query->where('is_required', (int) $isRequired);
            }

            if ($isFilterable !== null) {
                $query->where('is_filterable', (int) $isFilterable);
            }

            $query->orderBy($sortBy, $sortDir);
            $specifications = $query->paginate($perPage);

            $transformedData = $specifications->getCollection()->map(function ($spec) {
                return [
                    'id' => $spec->id,
                    'name' => $spec->name,
                    'code' => $spec->code,
                    'input_type' => $spec->input_type,
                    'input_type_label' => ucfirst($spec->input_type),
                    'is_required' => (bool) $spec->is_required,
                    'is_filterable' => (bool) $spec->is_filterable,
                    'sort_order' => $spec->sort_order,
                    'status' => (bool) $spec->status,
                    'values_count' => $spec->values_count,
                    'created_at' => $spec->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $spec->created_at->format('M d, Y'),
                    'updated_at' => $spec->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $specifications->currentPage(),
                    'per_page' => $specifications->perPage(),
                    'total' => $specifications->total(),
                    'last_page' => $specifications->lastPage(),
                ]
            ], 'Specifications retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specifications', 500);
        }
    }

    /**
     * Store a newly created specification.
     */
    public function store(SpecificationRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $specification = Specification::create($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $specification->id,
                'name' => $specification->name,
                'code' => $specification->code,
                'input_type' => $specification->input_type,
                'status' => (bool) $specification->status,
            ], 'Specification created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Specification store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create specification', 500);
        }
    }

    /**
     * Display the specified specification with its values.
     */
    public function show($id): JsonResponse
    {
        try {
            $specification = Specification::with(['values' => function($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }])->withCount('values')->find($id);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $specification->id,
                'name' => $specification->name,
                'code' => $specification->code,
                'input_type' => $specification->input_type,
                'input_type_label' => ucfirst($specification->input_type),
                'is_required' => (bool) $specification->is_required,
                'is_filterable' => (bool) $specification->is_filterable,
                'sort_order' => $specification->sort_order,
                'status' => (bool) $specification->status,
                'values_count' => $specification->values_count,
                'values' => $specification->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                        'sort_order' => $value->sort_order,
                        'status' => (bool) $value->status,
                        'created_at' => $value->created_at,
                    ];
                }),
                'created_at' => $specification->created_at,
                'updated_at' => $specification->updated_at,
            ], 'Specification retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification', 500);
        }
    }

    /**
     * Update the specified specification.
     */
    public function update(SpecificationRequest $request, $id): JsonResponse
    {
        try {
            $specification = Specification::find($id);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();

            $specification->update($data);

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $specification->id,
                'name' => $specification->name,
                'code' => $specification->code,
                'input_type' => $specification->input_type,
                'status' => (bool) $specification->status,
            ], 'Specification updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Specification update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification', 500);
        }
    }

    /**
     * Remove the specified specification.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $specification = Specification::find($id);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            // Check if specification has values
            if ($specification->values()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete specification. It has associated values.', 400);
            }

            // Check if specification is used in groups
            if ($specification->specificationGroups()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete specification. It is assigned to groups.', 400);
            }

            $specification->delete();

            return $this->apiResponse(true, null, 'Specification deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Specification delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete specification', 500);
        }
    }

    /**
     * Get specification statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = Specification::count();
            $active = Specification::where('status', 1)->count();
            $requiredSpecs = Specification::where('is_required', 1)->where('status', 1)->count();
            $filterableSpecs = Specification::where('is_filterable', 1)->where('status', 1)->count();

            // Get specification with most values
            $popularSpec = Specification::withCount('values')
                ->where('status', 1)
                ->orderBy('values_count', 'desc')
                ->first();

            // Count by input type
            $inputTypes = Specification::select('input_type', DB::raw('COUNT(*) as count'))
                ->where('status', 1)
                ->groupBy('input_type')
                ->get()
                ->pluck('count', 'input_type');

            return $this->apiResponse(true, [
                'total_specifications' => $total,
                'active_specifications' => $active,
                'required_specifications' => $requiredSpecs,
                'filterable_specifications' => $filterableSpecs,
                'popular_specification' => $popularSpec ? [
                    'id' => $popularSpec->id,
                    'name' => $popularSpec->name,
                    'code' => $popularSpec->code,
                    'values_count' => $popularSpec->values_count,
                ] : null,
                'input_types_count' => $inputTypes,
            ], 'Specification statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Get available input types.
     */
    public function inputTypes(): JsonResponse
    {
        try {
            $inputTypes = [
                ['value' => 'text', 'label' => 'Text', 'description' => 'Single line text input'],
                ['value' => 'textarea', 'label' => 'Text Area', 'description' => 'Multi-line text input'],
                ['value' => 'select', 'label' => 'Select', 'description' => 'Dropdown select box'],
                ['value' => 'multiselect', 'label' => 'Multi Select', 'description' => 'Multiple selection dropdown'],
                ['value' => 'radio', 'label' => 'Radio Buttons', 'description' => 'Radio button group'],
                ['value' => 'checkbox', 'label' => 'Checkbox', 'description' => 'Checkbox group'],
            ];

            return $this->apiResponse(true, $inputTypes, 'Input types retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification input types error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve input types', 500);
        }
    }

    /**
     * Toggle specification status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $specification = Specification::find($id);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $specification->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $specification->id,
                'status' => (bool) $specification->status,
            ], 'Specification status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Specification status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification status', 500);
        }
    }

    /**
     * Toggle required status.
     */
    public function toggleRequired(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'is_required' => 'required|boolean'
            ]);

            $specification = Specification::find($id);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            $specification->update(['is_required' => (int) $request->is_required]);

            return $this->apiResponse(true, [
                'id' => $specification->id,
                'is_required' => (bool) $specification->is_required,
            ], 'Specification required status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Specification required update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update required status', 500);
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

            $specification = Specification::find($id);

            if (!$specification) {
                return $this->apiResponse(false, null, 'Specification not found', 404);
            }

            $specification->update(['is_filterable' => (int) $request->is_filterable]);

            return $this->apiResponse(true, [
                'id' => $specification->id,
                'is_filterable' => (bool) $specification->is_filterable,
            ], 'Specification filterable status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Specification filterable update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update filterable status', 500);
        }
    }

    /**
     * Bulk update specifications.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:specifications,id',
                'field' => 'required|in:status,is_required,is_filterable',
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

            $updated = Specification::whereIn('id', $request->ids)
                ->update([$field => $value]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} specification(s) updated successfully");

        } catch (\Exception $e) {
            \Log::error('Specification bulk update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specifications', 500);
        }
    }

    /**
     * Bulk delete specifications.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:specifications,id',
            ]);

            // Check if any specification has values
            $specsWithValues = Specification::whereIn('id', $request->ids)
                ->has('values')
                ->count();

            if ($specsWithValues > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$specsWithValues} specification(s) that have associated values", 400);
            }

            // Check if any specification is used in groups
            $specsInGroups = Specification::whereIn('id', $request->ids)
                ->has('specificationGroups')
                ->count();

            if ($specsInGroups > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$specsInGroups} specification(s) that are assigned to groups", 400);
            }

            $deleted = Specification::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} specification(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Specification bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete specifications', 500);
        }
    }

    /**
     * Get specifications for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        try {
            $specifications = Specification::select('id', 'name', 'code', 'input_type', 'is_required')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function ($spec) {
                    return [
                        'id' => $spec->id,
                        'name' => $spec->name,
                        'code' => $spec->code,
                        'input_type' => $spec->input_type,
                        'is_required' => (bool) $spec->is_required,
                    ];
                });

            return $this->apiResponse(true, $specifications, 'Specifications retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specifications', 500);
        }
    }

    /**
     * Get specifications with values for product creation.
     */
    public function forProductCreation(): JsonResponse
    {
        try {
            $specifications = Specification::with(['values' => function($query) {
                $query->where('status', 1)
                      ->orderBy('sort_order')
                      ->orderBy('value');
            }])
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($spec) {
                return [
                    'id' => $spec->id,
                    'name' => $spec->name,
                    'code' => $spec->code,
                    'input_type' => $spec->input_type,
                    'is_required' => (bool) $spec->is_required,
                    'is_filterable' => (bool) $spec->is_filterable,
                    'values' => $spec->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                        ];
                    }),
                ];
            });

            return $this->apiResponse(true, $specifications, 'Specifications retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Product specifications error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specifications', 500);
        }
    }
}
