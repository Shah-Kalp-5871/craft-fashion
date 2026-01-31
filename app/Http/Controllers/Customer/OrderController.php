<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders()
    {
        $customer = Auth::guard('customer')->user();

        // Get all orders for the customer
        $orders = Order::where('customer_id', $customer->id)
            ->with(['items.variant.primaryImage.media', 'items.variant.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate status counts
        $statusCounts = [
            'pending' => Order::where('customer_id', $customer->id)->where('status', 'pending')->count(),
            'confirmed' => Order::where('customer_id', $customer->id)->where('status', 'confirmed')->count(),
            'processing' => Order::where('customer_id', $customer->id)->where('status', 'processing')->count(),
            'shipped' => Order::where('customer_id', $customer->id)->where('status', 'shipped')->count(),
            'delivered' => Order::where('customer_id', $customer->id)->where('status', 'delivered')->count(),
            'cancelled' => Order::where('customer_id', $customer->id)->where('status', 'cancelled')->count(),
            'refunded' => Order::where('customer_id', $customer->id)->where('status', 'refunded')->count(),
            'returned' => Order::where('customer_id', $customer->id)->where('status', 'returned')->count(),
        ];

        // Calculate order summary
        $totalOrders = $orders->total();
        $totalSpent = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['delivered', 'shipped', 'processing', 'confirmed'])
            ->sum('grand_total');
        $averageOrder = $totalOrders > 0 ? round($totalSpent / $totalOrders, 2) : 0;

        return view('customer.account.orders', compact(
            'orders',
            'statusCounts',
            'totalOrders',
            'totalSpent',
            'averageOrder'
        ));
    }

    public function orderDetails($id)
    {
        $customer = Auth::guard('customer')->user();

        // Get order with related data
        $order = Order::where('customer_id', $customer->id)
            ->with(['items'])
            ->findOrFail($id);

        // Get status history
        $statusHistory = OrderStatusHistory::where('order_id', $order->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Parse addresses
        $shippingAddress = $order->shipping_address;

        if (is_string($shippingAddress)) {
            $shippingAddress = json_decode($shippingAddress, true);
        } elseif (!is_array($shippingAddress)) {
            $shippingAddress = [];
        }

        $billingAddress = $order->billing_address;

        if (is_string($billingAddress)) {
            $billingAddress = json_decode($billingAddress, true);
        } elseif (!is_array($billingAddress)) {
            $billingAddress = $shippingAddress; // fallback to shipping address
        }


        return view('customer.account.order-details', compact(
            'order',
            'statusHistory',
            'shippingAddress',
            'billingAddress'
        ));
    }

    public function cancelOrder(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);

        $customer = Auth::guard('customer')->user();

        $order = Order::where('customer_id', $customer->id)
            ->where('id', $id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->firstOrFail();

        // Update order status
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now(),
        ]);

        // Add to status history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'notes' => 'Cancelled by customer: ' . $request->cancellation_reason,
        ]);

        return redirect()->route('customer.account.orders.details', $id)
            ->with('success', 'Order cancelled successfully.');
    }

    public function filterOrders($status)
    {
        $validStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'returned'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->route('customer.account.orders');
        }

        $customer = Auth::guard('customer')->user();

        $orders = Order::where('customer_id', $customer->id)
            ->where('status', $status)
            ->with(['items.variant.primaryImage.media', 'items.variant.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate status counts (same as orders method)
        $statusCounts = [
            'pending' => Order::where('customer_id', $customer->id)->where('status', 'pending')->count(),
            'confirmed' => Order::where('customer_id', $customer->id)->where('status', 'confirmed')->count(),
            'processing' => Order::where('customer_id', $customer->id)->where('status', 'processing')->count(),
            'shipped' => Order::where('customer_id', $customer->id)->where('status', 'shipped')->count(),
            'delivered' => Order::where('customer_id', $customer->id)->where('status', 'delivered')->count(),
            'cancelled' => Order::where('customer_id', $customer->id)->where('status', 'cancelled')->count(),
            'refunded' => Order::where('customer_id', $customer->id)->where('status', 'refunded')->count(),
            'returned' => Order::where('customer_id', $customer->id)->where('status', 'returned')->count(),
        ];

        $totalOrders = Order::where('customer_id', $customer->id)->count();
        $totalSpent = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['delivered', 'shipped', 'processing', 'confirmed'])
            ->sum('grand_total');
        $averageOrder = $totalOrders > 0 ? round($totalSpent / $totalOrders, 2) : 0;

        return view('customer.account.orders', compact(
            'orders',
            'statusCounts',
            'totalOrders',
            'totalSpent',
            'averageOrder',
            'status'
        ));
    }

    public function downloadInvoice($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('customer_id', $customer->id)
            ->with(['items.product', 'items.variant.attributes.attribute'])
            ->findOrFail($id);

        // Addresses are auto-casted to array by the Order model
        return view('admin.orders.invoice', compact('order'));
    }

    public function orderfailed()
    {
        return view('customer.account.orderfailed');
    }

    public function ordersuccess()
    {
        return view('customer.account.ordersuccess');
    }
}
