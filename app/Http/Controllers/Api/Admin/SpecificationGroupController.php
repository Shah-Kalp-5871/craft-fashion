<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\SpecificationGroupRequest;
use App\Models\SpecificationGroup;
use App\Models\Specification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecificationGroupController extends Controller
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
     * Display a listing of specification groups.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortDir = $request->get('sort_dir', 'asc');
            $status = $request->get('status');

            $query = SpecificationGroup::withCount('specifications');

            if ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            }

            if ($status !== null) {
                $query->where('status', (int) $status);
            }

            $query->orderBy($sortBy, $sortDir);
            $groups = $query->paginate($perPage);

            $transformedData = $groups->getCollection()->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'specifications_count' => $group->specifications_count,
                    'sort_order' => $group->sort_order,
                    'status' => (bool) $group->status,
                    'created_at' => $group->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $group->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $groups->currentPage(),
                    'per_page' => $groups->perPage(),
                    'total' => $groups->total(),
                    'last_page' => $groups->lastPage(),
                ]
            ], 'Specification groups retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification group index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification groups', 500);
        }
    }

    /**
     * Store a newly created specification group.
     */
    public function store(SpecificationGroupRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $specificationIds = $data['specification_ids'] ?? [];
            unset($data['specification_ids']);

            $group = SpecificationGroup::create($data);

            // Attach specifications if provided
            if (!empty($specificationIds)) {
                $group->specifications()->attach($specificationIds);
            }

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $group->id,
                'name' => $group->name,
                'status' => (bool) $group->status,
                'specifications_count' => count($specificationIds),
            ], 'Specification group created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Specification group store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create specification group', 500);
        }
    }

    /**
     * Display the specified specification group with its specifications.
     */
    public function show($id): JsonResponse
    {
        try {
            $group = SpecificationGroup::with(['specifications' => function($query) {
                $query->orderBy('sort_order')->orderBy('name');
            }])->find($id);

            if (!$group) {
                return $this->apiResponse(false, null, 'Specification group not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $group->id,
                'name' => $group->name,
                'specifications_count' => $group->specifications_count,
                'sort_order' => $group->sort_order,
                'status' => (bool) $group->status,
                'specifications' => $group->specifications->map(function ($spec) {
                    return [
                        'id' => $spec->id,
                        'name' => $spec->name,
                        'code' => $spec->code,
                        'input_type' => $spec->input_type,
                        'is_required' => (bool) $spec->is_required,
                        'is_filterable' => (bool) $spec->is_filterable,
                        'sort_order' => $spec->pivot->sort_order ?? 0,
                    ];
                }),
                'created_at' => $group->created_at,
                'updated_at' => $group->updated_at,
            ], 'Specification group retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification group show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification group', 500);
        }
    }

    /**
     * Update the specified specification group.
     */
    public function update(SpecificationGroupRequest $request, $id): JsonResponse
    {
        try {
            $group = SpecificationGroup::find($id);

            if (!$group) {
                return $this->apiResponse(false, null, 'Specification group not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();
            $specificationIds = $data['specification_ids'] ?? [];
            unset($data['specification_ids']);

            $group->update($data);

            // Sync specifications
            if (isset($request->specification_ids)) {
                $group->specifications()->sync($specificationIds);
            }

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $group->id,
                'name' => $group->name,
                'status' => (bool) $group->status,
                'specifications_count' => $group->specifications()->count(),
            ], 'Specification group updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Specification group update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification group', 500);
        }
    }

    /**
     * Remove the specified specification group.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $group = SpecificationGroup::find($id);

            if (!$group) {
                return $this->apiResponse(false, null, 'Specification group not found', 404);
            }

            // Check if group is used in categories
            if ($group->categories()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete group. It is assigned to categories.', 400);
            }

            $group->delete();

            return $this->apiResponse(true, null, 'Specification group deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Specification group delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete specification group', 500);
        }
    }

    /**
     * Get specification group statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = SpecificationGroup::count();
            $active = SpecificationGroup::where('status', 1)->count();

            // Get group with most specifications
            $largestGroup = SpecificationGroup::withCount('specifications')
                ->where('status', 1)
                ->orderBy('specifications_count', 'desc')
                ->first();

            // Get average specifications per group
            $averageSpecs = SpecificationGroup::where('status', 1)
                ->withCount('specifications')
                ->get()
                ->avg('specifications_count');

            return $this->apiResponse(true, [
                'total_groups' => $total,
                'active_groups' => $active,
                'largest_group' => $largestGroup ? [
                    'id' => $largestGroup->id,
                    'name' => $largestGroup->name,
                    'specifications_count' => $largestGroup->specifications_count,
                ] : null,
                'average_specifications' => $averageSpecs ? round($averageSpecs, 1) : 0,
            ], 'Specification group statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification group statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Toggle specification group status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $group = SpecificationGroup::find($id);

            if (!$group) {
                return $this->apiResponse(false, null, 'Specification group not found', 404);
            }

            $status = $request->status;
            if (is_bool($status) || $status === 'true' || $status === 'false') {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            } else {
                $status = (int) $status;
            }

            $group->update(['status' => $status]);

            return $this->apiResponse(true, [
                'id' => $group->id,
                'status' => (bool) $group->status,
            ], 'Specification group status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Specification group status update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update specification group status', 500);
        }
    }

    /**
     * Bulk update specification groups.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:specification_groups,id',
                'field' => 'required|in:status',
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
            }

            $updated = SpecificationGroup::whereIn('id', $request->ids)
                ->update([$field => $value]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} group(s) updated successfully");

        } catch (\Exception $e) {
            \Log::error('Specification group bulk update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update groups', 500);
        }
    }

    /**
     * Bulk delete specification groups.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:specification_groups,id',
            ]);

            // Check if any group is used in categories
            $groupsInCategories = SpecificationGroup::whereIn('id', $request->ids)
                ->has('categories')
                ->count();

            if ($groupsInCategories > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$groupsInCategories} group(s) that are assigned to categories", 400);
            }

            $deleted = SpecificationGroup::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} group(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Specification group bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete groups', 500);
        }
    }

    /**
     * Get specification groups for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        try {
            $groups = SpecificationGroup::select('id', 'name')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'name' => $group->name,
                    ];
                });

            return $this->apiResponse(true, $groups, 'Specification groups retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Specification group dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification groups', 500);
        }
    }

    /**
     * Get groups with specifications for category assignment.
     */
    public function forCategoryAssignment(): JsonResponse
    {
        try {
            $groups = SpecificationGroup::with(['specifications' => function($query) {
                $query->where('status', 1)
                      ->orderBy('sort_order')
                      ->orderBy('name');
            }])
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'specifications' => $group->specifications->map(function ($spec) {
                        return [
                            'id' => $spec->id,
                            'name' => $spec->name,
                            'code' => $spec->code,
                            'input_type' => $spec->input_type,
                            'is_required' => (bool) $spec->is_required,
                        ];
                    }),
                ];
            });

            return $this->apiResponse(true, $groups, 'Specification groups retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Category groups error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve specification groups', 500);
        }
    }
}
