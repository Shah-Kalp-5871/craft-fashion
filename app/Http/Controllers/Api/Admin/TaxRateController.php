<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\TaxRateRequest;
use App\Models\TaxClass;
use App\Models\TaxRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxRateController extends Controller
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
     * Display a listing of tax rates.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');
            $taxClassId = $request->get('tax_class_id');
            $isActive = $request->get('is_active');

            $query = TaxRate::with('taxClass');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('country_code', 'LIKE', "%{$search}%")
                        ->orWhere('state_code', 'LIKE', "%{$search}%")
                        ->orWhere('zip_code', 'LIKE', "%{$search}%");
                });
            }

            if ($taxClassId) {
                $query->where('tax_class_id', $taxClassId);
            }

            if ($isActive !== null) {
                $query->where('is_active', $isActive);
            }

            $query->orderBy($sortBy, $sortDir);
            $taxRates = $query->paginate($perPage);

            $transformedData = $taxRates->getCollection()->map(function ($taxRate) {
                return [
                    'id' => $taxRate->id,
                    'name' => $taxRate->name,
                    'tax_class_id' => $taxRate->tax_class_id,
                    'tax_class_name' => $taxRate->taxClass?->name,
                    'country_code' => $taxRate->country_code,
                    'state_code' => $taxRate->state_code,
                    'zip_code' => $taxRate->zip_code,
                    'rate' => (float) $taxRate->rate,
                    'formatted_rate' => number_format($taxRate->rate, 2) . '%',
                    'is_active' => (bool) $taxRate->is_active,
                    'priority' => $taxRate->priority,
                    'location' => $this->getLocationLabel($taxRate),
                    'created_at' => $taxRate->created_at,
                    'created_at_formatted' => $taxRate->created_at->format('M d, Y'),
                    'updated_at' => $taxRate->updated_at,
                ];
            });

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $taxRates->currentPage(),
                    'per_page' => $taxRates->perPage(),
                    'total' => $taxRates->total(),
                    'last_page' => $taxRates->lastPage(),
                ]
            ], 'Tax rates retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax rates', 500);
        }
    }

    /**
     * Store a newly created tax rate.
     */
    public function store(TaxRateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

            $taxRate = TaxRate::create($data);

            return $this->apiResponse(true, [
                'id' => $taxRate->id,
                'name' => $taxRate->name,
                'rate' => $taxRate->rate,
                'tax_class_name' => $taxRate->taxClass?->name,
                'is_active' => $taxRate->is_active,
            ], 'Tax rate created successfully', 201);

        } catch (\Exception $e) {
            \Log::error('Tax rate store error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to create tax rate', 500);
        }
    }

    /**
     * Display the specified tax rate.
     */
    public function show($id): JsonResponse
    {
        try {
            $taxRate = TaxRate::with('taxClass')->find($id);

            if (!$taxRate) {
                return $this->apiResponse(false, null, 'Tax rate not found', 404);
            }

            return $this->apiResponse(true, [
                'id' => $taxRate->id,
                'name' => $taxRate->name,
                'tax_class_id' => $taxRate->tax_class_id,
                'tax_class_name' => $taxRate->taxClass?->name,
                'country_code' => $taxRate->country_code,
                'state_code' => $taxRate->state_code,
                'zip_code' => $taxRate->zip_code,
                'rate' => (float) $taxRate->rate,
                'formatted_rate' => number_format((float) $taxRate->rate, 2) . '%',
                'is_active' => (bool) $taxRate->is_active,
                'priority' => $taxRate->priority,
                'location' => $this->getLocationLabel($taxRate),
                'created_at' => $taxRate->created_at,
                'updated_at' => $taxRate->updated_at,
            ], 'Tax rate retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate show error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax rate', 500);
        }
    }

    /**
     * Update the specified tax rate.
     */
    public function update(TaxRateRequest $request, $id): JsonResponse
    {
        try {
            $taxRate = TaxRate::find($id);

            if (!$taxRate) {
                return $this->apiResponse(false, null, 'Tax rate not found', 404);
            }

            $data = $request->validated();
            $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : $taxRate->is_active;

            $taxRate->update($data);

            return $this->apiResponse(true, [
                'id' => $taxRate->id,
                'name' => $taxRate->name,
                'rate' => $taxRate->rate,
                'tax_class_name' => $taxRate->taxClass?->name,
                'is_active' => $taxRate->is_active,
            ], 'Tax rate updated successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate update error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update tax rate', 500);
        }
    }

    /**
     * Remove the specified tax rate.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $taxRate = TaxRate::find($id);

            if (!$taxRate) {
                return $this->apiResponse(false, null, 'Tax rate not found', 404);
            }

            $taxRate->delete();

            return $this->apiResponse(true, null, 'Tax rate deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete tax rate', 500);
        }
    }

    /**
     * Get tax rate statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $total = TaxRate::count();
            $active = TaxRate::where('is_active', true)->count();
            $inactive = $total - $active;

            $averageRate = TaxRate::where('is_active', true)->avg('rate') ?? 0;

            // Get most common tax rate value
            $commonRate = TaxRate::select('rate', DB::raw('COUNT(*) as count'))
                ->where('is_active', true)
                ->groupBy('rate')
                ->orderBy('count', 'desc')
                ->first();

            $ratesByCountry = TaxRate::select('country_code', DB::raw('COUNT(*) as count'))
                ->whereNotNull('country_code')
                ->where('is_active', true)
                ->groupBy('country_code')
                ->get()
                ->pluck('count', 'country_code');

            return $this->apiResponse(true, [
                'total_rates' => $total,
                'active_rates' => $active,
                'inactive_rates' => $inactive,
                'average_rate' => number_format($averageRate, 2),
                'common_rate' => $commonRate ? number_format($commonRate->rate, 2) . '%' : 'N/A',
                'rates_by_country' => $ratesByCountry,
            ], 'Tax rate statistics retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate statistics error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Toggle tax rate status.
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        try {
            $taxRate = TaxRate::find($id);

            if (!$taxRate) {
                return $this->apiResponse(false, null, 'Tax rate not found', 404);
            }

            $taxRate->update([
                'is_active' => !$taxRate->is_active
            ]);

            return $this->apiResponse(true, [
                'id' => $taxRate->id,
                'is_active' => $taxRate->is_active,
            ], 'Tax rate status updated successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate toggle status error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update status', 500);
        }
    }

    /**
     * Bulk delete tax rates.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:tax_rates,id',
            ]);

            $deleted = TaxRate::whereIn('id', $request->ids)->delete();

            return $this->apiResponse(true, [
                'deleted_count' => $deleted,
            ], "{$deleted} tax rate(s) deleted successfully");

        } catch (\Exception $e) {
            \Log::error('Tax rate bulk delete error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to delete tax rates', 500);
        }
    }

    /**
     * Bulk update tax rate status.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:tax_rates,id',
                'is_active' => 'required|boolean',
            ]);

            $updated = TaxRate::whereIn('id', $request->ids)
                ->update(['is_active' => $request->is_active]);

            return $this->apiResponse(true, [
                'updated_count' => $updated,
            ], "{$updated} tax rate(s) status updated successfully");

        } catch (\Exception $e) {
            \Log::error('Tax rate bulk status error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to update status', 500);
        }
    }

    /**
     * Get tax types for dropdown.
     */
    public function types(): JsonResponse
    {
        try {
            $types = [
                ['value' => 'percentage', 'label' => 'Percentage (%)'],
                ['value' => 'fixed', 'label' => 'Fixed Amount'],
            ];

            return $this->apiResponse(true, $types, 'Tax types retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate types error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax types', 500);
        }
    }

    /**
     * Get tax scopes for dropdown.
     */
    public function scopes(): JsonResponse
    {
        try {
            $scopes = [
                ['value' => 'national', 'label' => 'National'],
                ['value' => 'state', 'label' => 'State/Province'],
                ['value' => 'local', 'label' => 'Local/City'],
                ['value' => 'special', 'label' => 'Special Zone'],
            ];

            return $this->apiResponse(true, $scopes, 'Tax scopes retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Tax rate scopes error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve tax scopes', 500);
        }
    }

    /**
     * Helper: Get location label for tax rate.
     */
    private function getLocationLabel(TaxRate $taxRate): string
    {
        $parts = [];

        if ($taxRate->country_code) {
            $parts[] = $taxRate->country_code;
        }

        if ($taxRate->state_code) {
            $parts[] = $taxRate->state_code;
        }

        if ($taxRate->zip_code) {
            $parts[] = $taxRate->zip_code;
        }

        return $parts ? implode(', ', $parts) : 'All Locations';
    }

    /**
     * Calculate tax for a given amount.
     */
    public function calculate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'tax_rate_id' => 'nullable|exists:tax_rates,id',
                'rate' => 'nullable|numeric|min:0|max:100',
            ]);

            $amount = (float) $request->amount;
            $rate = 0;

            if ($request->has('tax_rate_id')) {
                $taxRate = TaxRate::find($request->tax_rate_id);
                if ($taxRate && $taxRate->is_active) {
                    $rate = (float) $taxRate->rate;
                }
            } elseif ($request->has('rate')) {
                $rate = (float) $request->rate;
            }

            $taxAmount = ($amount * $rate) / 100;
            $totalAmount = $amount + $taxAmount;

            return $this->apiResponse(true, [
                'original_amount' => number_format($amount, 2),
                'tax_rate' => number_format($rate, 2) . '%',
                'tax_amount' => number_format($taxAmount, 2),
                'total_amount' => number_format($totalAmount, 2),
            ], 'Tax calculated successfully');

        } catch (\Exception $e) {
            \Log::error('Tax calculate error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to calculate tax', 500);
        }
    }
}
