<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
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
     * Display a listing of product variants with stock levels.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [];
            
            // Handle Tabulator's filter format if present
            if ($request->has('filters')) {
                foreach ($request->get('filters') as $filter) {
                    if (isset($filter['field']) && isset($filter['value'])) {
                        $filters[$filter['field']] = $filter['value'];
                    }
                }
            } else {
                // Fallback to direct parameters
                $filters = $request->only(['search', 'category_id', 'brand_id', 'stock_status']);
            }

            // Handle sorting
            $sortBy = 'updated_at';
            $sortDir = 'desc';
            if ($request->has('sorters')) {
                $sorter = $request->get('sorters')[0] ?? null;
                if ($sorter) {
                    $sortBy = $sorter['field'];
                    $sortDir = $sorter['dir'];
                }
            }
            
            $filters['sort_by'] = $sortBy;
            $filters['sort_dir'] = $sortDir;

            $perPage = $request->get('size', 10); // Tabulator uses 'size' for per page

            $variants = $this->inventoryService->getVariants($filters, $perPage);

            $transformedData = $variants->getCollection()->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->product ? $variant->product->name : 'N/A',
                    'sku' => $variant->sku,
                    'category_id' => $variant->product ? $variant->product->main_category_id : null,
                    'category_name' => $variant->product && $variant->product->mainCategory ? $variant->product->mainCategory->name : 'N/A',
                    'brand_id' => $variant->product ? $variant->product->brand_id : null,
                    'brand_name' => $variant->product && $variant->product->brand ? $variant->product->brand->name : 'N/A',
                    'current_stock' => $variant->stock_quantity,
                    'min_stock' => 10, // Default min stock for UI purposes
                    'status' => $variant->stock_status,
                    'image' => $variant->display_image ? asset('storage/' . $variant->display_image) : null,
                    'last_updated' => $variant->updated_at->format('Y-m-d H:i'),
                ];
            })->values()->all();

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $variants->currentPage(),
                    'per_page' => $variants->perPage(),
                    'total' => $variants->total(),
                    'last_page' => $variants->lastPage(),
                ]
            ], 'Inventory data retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Inventory index error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve inventory data', 500);
        }
    }

    /**
     * Get inventory statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->inventoryService->getStatistics();
            return $this->apiResponse(true, $stats, 'Statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Inventory stats error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }

    /**
     * Update stock for a variant.
     */
    public function updateStock(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:product_variants,id',
                'quantity' => 'required|integer|min:0',
                'type' => 'required|in:add,remove,set',
                'reason' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            $result = $this->inventoryService->updateStock(
                $request->variant_id,
                $request->quantity,
                $request->type,
                $request->reason,
                null,
                $request->notes
            );

            if ($result['success']) {
                return $this->apiResponse(true, $result['variant'], 'Stock updated successfully');
            } else {
                return $this->apiResponse(false, null, $result['error'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Stock update API error: ' . $e->getMessage());
            return $this->apiResponse(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update stock for multiple variants.
     */
    public function bulkUpdateStock(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'variant_ids' => 'required|array',
                'variant_ids.*' => 'exists:product_variants,id',
                'quantity' => 'required|integer|min:0',
                'type' => 'required|in:add,remove,set',
                'reason' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            $results = [];
            foreach ($request->variant_ids as $id) {
                $results[] = $this->inventoryService->updateStock(
                    $id,
                    $request->quantity,
                    $request->type,
                    $request->reason,
                    null,
                    $request->notes
                );
            }

            return $this->apiResponse(true, $results, 'Bulk stock update processed');

        } catch (\Exception $e) {
            Log::error('Bulk stock update API error: ' . $e->getMessage());
            return $this->apiResponse(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Get stock history.
     */
    public function history(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'change_type', 'start_date', 'end_date', 'variant_id']);
            $perPage = $request->get('per_page') ?: $request->get('size', 15);

            $history = $this->inventoryService->getHistory($filters, $perPage);

            $transformedData = $history->getCollection()->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'product_name' => $entry->variant && $entry->variant->product ? $entry->variant->product->name : 'N/A',
                    'sku' => $entry->variant ? $entry->variant->sku : 'N/A',
                    'action' => $entry->change_type,
                    'quantity' => $entry->quantity,
                    'old_stock' => $entry->old_quantity,
                    'new_stock' => $entry->new_quantity,
                    'reason' => $entry->reason,
                    'updated_by' => $entry->admin ? $entry->admin->name : 'System',
                    'notes' => $entry->notes,
                    'updated_at' => $entry->created_at->format('Y-m-d H:i'),
                ];
            })->values()->all();

            return $this->apiResponse(true, [
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $history->currentPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                    'last_page' => $history->lastPage(),
                ]
            ], 'Stock history retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Inventory history error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve stock history', 500);
        }
    }

    /**
     * Get stock history statistics.
     */
    public function historyStatistics(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['start_date', 'end_date', 'variant_id']);
            $stats = $this->inventoryService->historyStatistics($filters);
            return $this->apiResponse(true, $stats, 'History statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Inventory history stats error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to retrieve statistics', 500);
        }
    }
}
