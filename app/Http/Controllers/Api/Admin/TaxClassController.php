<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\TaxClassRequest;
use App\Models\TaxClass;
use App\Models\TaxRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TaxClassController extends Controller
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
     * Display a listing of tax classes.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');

            $query = TaxClass::with(['rates' => function($q) {
                $q->where('is_active', true);
            }])->withCount(['rates', 'products']);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            $query->orderBy($sortBy, $sortDir);
            $taxClasses = $query->paginate($perPage);

            $transformedData = $taxClasses->getCollection()->map(function ($taxClass) {
                // Calculate total rate
                $totalRate = $taxClass->rates->sum('rate');

                return [
                    'id' => $taxClass->id,
                    'name' => $taxClass->name,
                    'code' => $taxClass->code,
                    'description' => $taxClass->description,
                    'is_default' => (bool) $taxClass->is_default,
                    'is_active' => true, // Default active for tax classes
                    'total_rate' => number_format($totalRate, 2),
                    'tax_rates_count' => $taxClass->rates_count,
                    'products_count' => $taxClass->products_count,
                    'created_at' => $taxClass->created_at,
                    'created_at_formatted' => $taxClass->created_at->format('M d, Y'),
                    'updated_at' => $taxClass->updated_at,
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $taxClasses->currentPage(),
                    'per_page' => $taxClasses->perPage(),
                    'total' => $taxClasses->total(),
                    'last_page' => $taxClasses->lastPage(),
                ]
            ], 'Tax classes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax class index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax classes', 500);
        }
    }

    /**
     * Store a newly created tax class.
     */
    public function store(TaxClassRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['code'] ??= strtoupper(Str::slug($data['name'], '_'));

            $data['is_default'] = $request->has('is_default') ? (bool) $request->is_default : false;

            // If setting as default, unset other defaults
            if ($data['is_default']) {
                TaxClass::where('is_default', true)->update(['is_default' => false]);
            }

            $taxClass = TaxClass::create($data);

            // Attach tax rates if provided
            if ($request->has('tax_rate_ids') && is_array($request->tax_rate_ids)) {
if ($request->filled('tax_rate_ids')) {
    TaxRate::whereIn('id', $request->tax_rate_ids)
        ->update(['tax_class_id' => $taxClass->id]);
}
            }

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $taxClass->id,
                'name' => $taxClass->name,
                'code' => $taxClass->code,
                'is_default' => $taxClass->is_default,
            ], 'Tax class created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tax class store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create tax class', 500);
        }
    }

    /**
     * Display the specified tax class.
     */
    public function show($id): JsonResponse
    {
        try {
            $taxClass = TaxClass::with(['rates' => function($q) {
                $q->orderBy('priority', 'asc');
            }])->withCount(['rates', 'products'])->find($id);

            if (!$taxClass) {
                return $this->apiResponse(false, null, 'Tax class not found', 404);
            }

            $totalRate = $taxClass->rates->sum('rate');

            return $this->apiResponse(true, [
                'id' => $taxClass->id,
                'name' => $taxClass->name,
                'code' => $taxClass->code,
                'description' => $taxClass->description,
                'is_default' => (bool) $taxClass->is_default,
                'total_rate' => number_format($totalRate, 2),
                'tax_rates' => $taxClass->rates->map(function ($rate) {
                    return [
                        'id' => $rate->id,
                        'name' => $rate->name,
                        'rate' => number_format($rate->rate, 2),
                        'formatted_rate' => number_format($rate->rate, 2) . '%',
                        'country_code' => $rate->country_code,
                        'state_code' => $rate->state_code,
                        'is_active' => (bool) $rate->is_active,
                        'priority' => $rate->priority,
                    ];
                }),
                'tax_rates_count' => $taxClass->rates_count,
                'products_count' => $taxClass->products_count,
                'created_at' => $taxClass->created_at,
                'updated_at' => $taxClass->updated_at,
            ], 'Tax class retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax class show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax class', 500);
        }
    }

    /**
     * Update the specified tax class.
     */
    public function update(TaxClassRequest $request, $id): JsonResponse
    {
        try {
            $taxClass = TaxClass::find($id);

            if (!$taxClass) {
                return $this->apiResponse(false, null, 'Tax class not found', 404);
            }

            DB::beginTransaction();

            $data = $request->validated();
            $data['is_default'] = $request->has('is_default') ? (bool) $request->is_default : $taxClass->is_default;

            // If setting as default, unset other defaults
            if ($data['is_default'] && !$taxClass->is_default) {
                TaxClass::where('is_default', true)->update(['is_default' => false]);
            }

            $taxClass->update($data);

            // Update tax rates if provided
            if ($request->has('tax_rate_ids') && is_array($request->tax_rate_ids)) {
if ($request->filled('tax_rate_ids')) {
    TaxRate::whereIn('id', $request->tax_rate_ids)
        ->update(['tax_class_id' => $taxClass->id]);
}
            }

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $taxClass->id,
                'name' => $taxClass->name,
                'code' => $taxClass->code,
                'is_default' => $taxClass->is_default,
            ], 'Tax class updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tax class update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update tax class', 500);
        }
    }

    /**
     * Remove the specified tax class.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $taxClass = TaxClass::find($id);

            if (!$taxClass) {
                return $this->apiResponse(false, null, 'Tax class not found', 404);
            }

            // Check if tax class has products
            if ($taxClass->products()->exists()) {
                return $this->apiResponse(false, null, 'Cannot delete tax class. It has associated products.', 400);
            }

            // Check if it's the default tax class
            if ($taxClass->is_default) {
                return $this->apiResponse(false, null, 'Cannot delete the default tax class.', 400);
            }

            $taxClass->delete();

            return $this->apiResponse(true, null, 'Tax class deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Tax class delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete tax class', 500);
        }
    }

    /**
     * Get tax class statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = TaxClass::count();
            $active = TaxClass::count(); // All tax classes are considered active
            $default = TaxClass::where('is_default', true)->first();

            return $this->apiResponse(true, [
                'total_classes' => $total,
                'active_classes' => $active,
                'default_class' => $default ? [
                    'id' => $default->id,
                    'name' => $default->name,
                    'code' => $default->code,
                ] : null,
                'classes_with_rates' => TaxClass::has('rates')->count(),
                'classes_without_rates' => TaxClass::doesntHave('rates')->count(),
            ], 'Tax class statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax class statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Toggle tax class default status.
     */
    public function toggleDefault(Request $request, $id): JsonResponse
    {
        try {
            $taxClass = TaxClass::find($id);

            if (!$taxClass) {
                return $this->apiResponse(false, null, 'Tax class not found', 404);
            }

            DB::beginTransaction();

            // If setting as default, unset other defaults
            if (!$taxClass->is_default) {
                TaxClass::where('is_default', true)->update(['is_default' => false]);
                $taxClass->update(['is_default' => true]);
                $message = 'Tax class set as default';
            } else {
                // Cannot unset default if it's the only one
                $defaultCount = TaxClass::where('is_default', true)->count();
                if ($defaultCount === 1) {
                    return $this->apiResponse(false, null, 'Cannot unset the only default tax class', 400);
                }
                $taxClass->update(['is_default' => false]);
                $message = 'Tax class removed from default';
            }

            DB::commit();

            return $this->apiResponse(true, [
                'id' => $taxClass->id,
                'is_default' => $taxClass->is_default,
            ], $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tax class toggle default error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update default status', 500);
        }
    }

    /**
     * Bulk delete tax classes.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:tax_classes,id',
            ]);

            // Check if any tax class has products
            $classesWithProducts = TaxClass::whereIn('id', $request->ids)
                ->has('products')
                ->count();

            if ($classesWithProducts > 0) {
                return $this->apiResponse(false, null, "Cannot delete {$classesWithProducts} tax class(es) that have associated products", 400);
            }

            // Check if trying to delete default tax class
            $defaultClasses = TaxClass::whereIn('id', $request->ids)
                ->where('is_default', true)
                ->count();

            if ($defaultClasses > 0) {
                return $this->apiResponse(false, null, "Cannot delete default tax class", 400);
            }

            $deleted = TaxClass::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} tax class(es) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Tax class bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete tax classes', 500);
        }
    }

    /**
     * Get available tax classes for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        try {
            $taxClasses = TaxClass::select('id', 'name', 'code', 'is_default')
                ->orderBy('is_default', 'desc')
                ->orderBy('name')
                ->get()
                ->map(function ($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'code' => $class->code,
                        'is_default' => (bool) $class->is_default,
                    ];
                });

            return $this->apiResponse(true, $taxClasses, 'Tax classes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax class dropdown error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax classes', 500);
        }
    }
}
