<?php

namespace App\Services\Admin;

use App\Models\ProductVariant;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Get product variants with stock information and filters.
     */
    public function getVariants(array $filters = [], int $perPage = 10)
    {
        $query = ProductVariant::with(['product:id,name,main_category_id,brand_id', 'product.mainCategory:id,name', 'product.brand:id,name', 'primaryImage.media']);

        // Search by SKU or Product Name
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by Category
        if (!empty($filters['category_id'])) {
            $query->whereHas('product', function($q) use ($filters) {
                $q->where('main_category_id', $filters['category_id']);
            });
        }

        // Filter by Brand
        if (!empty($filters['brand_id'])) {
            $query->whereHas('product', function($q) use ($filters) {
                $q->where('brand_id', $filters['brand_id']);
            });
        }

        // Filter by Stock Status
        if (!empty($filters['stock_status'])) {
            $query->where('stock_status', $filters['stock_status']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'updated_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    /**
     * Update stock for a specific variant.
     */
    public function updateStock(int $variantId, int $quantity, string $type, string $reason, $reference = null, $notes = null)
    {
        try {
            DB::beginTransaction();

            $variant = ProductVariant::findOrFail($variantId);
            $oldQuantity = $variant->stock_quantity;
            $newQuantity = $oldQuantity;

            switch ($type) {
                case 'add':
                    $newQuantity = $oldQuantity + $quantity;
                    break;
                case 'remove':
                    $newQuantity = max(0, $oldQuantity - $quantity);
                    $quantity = $oldQuantity - $newQuantity; // Actual quantity removed
                    break;
                case 'set':
                    $newQuantity = $quantity;
                    $quantity = abs($newQuantity - $oldQuantity); // Magnitude of change
                    break;
            }

            // Update variant stock
            $variant->stock_quantity = $newQuantity;
            
            // Update stock status based on new quantity
            $variant->stock_status = $this->calculateStockStatus($newQuantity);
            $variant->save();

            // Log to stock history
            StockHistory::create([
                'product_variant_id' => $variant->id,
                'change_type' => $type === 'set' ? ($newQuantity >= $oldQuantity ? 'add' : 'remove') : $type,
                'quantity' => $quantity,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $reason,
                'source_type' => 'admin',
                'source_id' => auth()->id(),
                'admin_id' => auth()->id(),
                'notes' => $notes,
            ]);

            DB::commit();
            return ['success' => true, 'variant' => $variant];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock update error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get inventory statistics.
     */
    public function getStatistics()
    {
        $totalVariants = ProductVariant::count();
        $outOfStock = ProductVariant::where('stock_quantity', '<=', 0)->count();
        $lowStock = ProductVariant::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10) // Assuming 10 is low stock threshold
            ->count();
        $inStock = ProductVariant::where('stock_quantity', '>', 10)->count();

        return [
            'total_items' => $totalVariants,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'in_stock' => $inStock,
            'last_updated' => ProductVariant::max('updated_at'),
        ];
    }

    public function historyStatistics(array $filters = [])
    {
        $query = StockHistory::query();

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['variant_id'])) {
            $query->where('product_variant_id', $filters['variant_id']);
        }

        $stats = [
            'total_adjustments' => (clone $query)->count(),
            'stock_added' => (clone $query)->where('change_type', 'add')->sum('quantity'),
            'stock_removed' => (clone $query)->where('change_type', 'remove')->sum('quantity'),
        ];

        return $stats;
    }

    /**
     * Get stock history with filters.
     */
    public function getHistory(array $filters = [], int $perPage = 15)
    {
        $query = StockHistory::with(['variant.product', 'admin:id,name']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('variant', function($q) use ($search) {
                $q->where('sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['variant_id'])) {
            $query->where('product_variant_id', $filters['variant_id']);
        }

        if (!empty($filters['change_type'])) {
            $query->where('change_type', $filters['change_type']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Helper to calculate stock status.
     */
    private function calculateStockStatus(int $quantity): string
    {
        if ($quantity <= 0) {
            return 'out_of_stock';
        } elseif ($quantity <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
}
