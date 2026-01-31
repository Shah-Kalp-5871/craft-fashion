<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display listing page
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Show order details page
     */
    public function view(Order $order)
    {
        $order->load(['customer', 'items', 'items.product', 'items.variant', 'payments', 'shipments']);
        return view('admin.orders.view', compact('order'));
    }

    /**
     * AJAX: Get orders data
     */
    public function getOrders(Request $request)
    {
        $query = Order::with(['customer', 'items', 'payments'])
            ->withCount(['items'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });
            });
        }


        // Sorting
        if ($request->has('sort')) {
            $sortField = $request->sort;
            $sortDirection = $request->direction ?? 'desc';
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }

        $orders = $query->paginate($request->per_page ?? 10)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_email' => $order->customer->email ?? 'N/A',
                    'customer_mobile' => $order->customer->mobile ?? 'N/A',
                    'date' => $order->created_at->format('Y-m-d'),
                    'created_at' => $order->created_at,
                    'items_count' => $order->items_count,
                    'grand_total' => $order->grand_total,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => match ($order->payment_method) {
                        'cod' => 'Cash on Delivery',
                        'online' => 'Online Payment',
                        default => 'N/A',
                    },

                    'shipping_address' => $order->shipping_address ? implode(', ', array_filter($order->shipping_address)) : 'N/A',
                    'tracking_number' => $order->shipments->first()->tracking_number ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ]
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $order->status = $validated['status'];

            // Set timestamps based on status
            switch ($validated['status']) {
                case 'confirmed':
                    $order->confirmed_at = now();
                    break;
                case 'processing':
                    $order->processing_at = now();
                    break;
                case 'shipped':
                    $order->shipped_at = now();
                    break;
                case 'delivered':
                    $order->delivered_at = now();
                    break;
                case 'cancelled':
                    $order->cancelled_at = now();
                    break;
            }

            $order->save();

            // Create status history
            $order->statusHistory()->create([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'changed_by' => auth('admin')->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'status' => $order->status,
                'old_status' => $oldStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating order status. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,partially_paid,failed,refunded',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldPaymentStatus = $order->payment_status;
            $order->payment_status = $validated['payment_status'];
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully!',
                'payment_status' => $order->payment_status,
                'old_payment_status' => $oldPaymentStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating payment status. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Delete order
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Soft delete the order
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting order. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Bulk delete orders
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        try {
            DB::beginTransaction();

            $count = Order::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$count} order(s) deleted successfully!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk deleting orders: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting orders. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Export orders
     */
    public function export(Request $request)
    {
        $query = Order::with(['customer', 'items']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->get()->map(function ($order) {
            return [
                'Order ID' => $order->order_number,
                'Customer' => $order->customer->name ?? 'N/A',
                'Email' => $order->customer->email ?? 'N/A',
                'Phone' => $order->customer->mobile ?? 'N/A',
                'Order Date' => $order->created_at->format('Y-m-d H:i'),
                'Status' => ucfirst($order->status),
                'Payment Status' => ucfirst(str_replace('_', ' ', $order->payment_status)),
                'Payment Method' => match ($order->payment_method) {
                    'cod' => 'Cash on Delivery',
                    'online' => 'Online Payment',
                    default => 'N/A',
                },

                'Items Count' => $order->items->count(),
                'Subtotal' => number_format((float) $order->subtotal, 2),
                'Tax' => number_format((float) $order->tax_total, 2),
                'Shipping' => number_format((float) $order->shipping_total, 2),
                'Discount' => number_format((float) $order->discount_total, 2),
                'Grand Total' => number_format((float) $order->grand_total, 2),
                'Shipping Address' => $order->shipping_address ? implode(', ', array_filter($order->shipping_address)) : 'N/A',
            ];
        });

        $filename = 'orders_' . date('Y-m-d_H-i') . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            if (count($orders) > 0) {
                fputcsv($file, array_keys($orders[0]));
            }

            // Add data rows
            foreach ($orders as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print invoice
     */
    public function printInvoice(Order $order)
    {
        $order->load(['customer', 'items', 'items.product', 'items.variant']);

        // Return view for printing
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Update tracking number
     */
    public function updateTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
            'carrier' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Create or update shipment
            $shipment = $order->shipments()->create([
                'tracking_number' => $validated['tracking_number'],
                'carrier' => $validated['carrier'] ?? 'Standard',
                'status' => 'shipped',
                'shipped_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Add all order items to shipment
            foreach ($order->items as $item) {
                $shipment->items()->create([
                    'order_item_id' => $item->id,
                    'quantity' => $item->quantity,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tracking number updated successfully!',
                'tracking_number' => $validated['tracking_number'],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating tracking number: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating tracking number. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
