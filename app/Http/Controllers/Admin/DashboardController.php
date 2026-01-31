<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==================== REGULAR VIEW RENDER ====================

        // Get date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // ==================== STATISTICS ====================

        // Total Revenue (current month)
        $currentMonthRevenue = DB::table('orders')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('grand_total');

        $lastMonthRevenue = DB::table('orders')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('grand_total');

        $revenueChange = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : ($currentMonthRevenue > 0 ? 100 : 0);

        // Total Orders (current month)
        $currentMonthOrders = DB::table('orders')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $lastMonthOrders = DB::table('orders')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $ordersChange = $lastMonthOrders > 0
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
            : ($currentMonthOrders > 0 ? 100 : 0);

        // Total Products
        $totalProducts = DB::table('products')->whereNull('deleted_at')->count();
        $lastMonthProducts = DB::table('products')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $currentMonthProducts = DB::table('products')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $productsChange = $lastMonthProducts > 0
            ? (($currentMonthProducts - $lastMonthProducts) / $lastMonthProducts) * 100
            : ($currentMonthProducts > 0 ? 100 : 0);

        // Total Customers
        $totalCustomers = DB::table('customers')->whereNull('deleted_at')->count();
        $lastMonthCustomers = DB::table('customers')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $currentMonthCustomers = DB::table('customers')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $customersChange = $lastMonthCustomers > 0
            ? (($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100
            : ($currentMonthCustomers > 0 ? 100 : 0);

        // Today's Stats
        $todayOrders = DB::table('orders')
            ->whereDate('created_at', $today)
            ->count();

        $todayRevenue = DB::table('orders')
            ->where('status', '!=', 'cancelled')
            ->whereDate('created_at', $today)
            ->sum('grand_total');

        $yesterdayOrders = DB::table('orders')
            ->whereDate('created_at', $yesterday)
            ->count();

        $yesterdayRevenue = DB::table('orders')
            ->where('status', '!=', 'cancelled')
            ->whereDate('created_at', $yesterday)
            ->sum('grand_total');

        // Pending Orders Count
        $pendingOrders = DB::table('orders')
            ->where('status', 'pending')
            ->count();

        // Low Stock Products (stock_quantity < 10)
        $lowStockProducts = DB::table('product_variants')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereNull('products.deleted_at')
            ->where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->count();

        // Out of Stock Products
        $outOfStockProducts = DB::table('product_variants')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereNull('products.deleted_at')
            ->where('stock_quantity', '<=', 0)
            ->count();

        // Abandoned Carts (not updated in last 24 hours)
        $abandonedCarts = DB::table('carts')
            ->where('status', 'active')
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->count();

        // Dashboard statistics
        $stats = [
            'total_revenue' => $currentMonthRevenue,
            'total_orders' => $currentMonthOrders,
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'revenue_change' => round($revenueChange, 1),
            'orders_change' => round($ordersChange, 1),
            'products_change' => round($productsChange, 1),
            'customers_change' => round($customersChange, 1),
            'today_orders' => $todayOrders,
            'today_revenue' => $todayRevenue,
            'yesterday_orders' => $yesterdayOrders,
            'yesterday_revenue' => $yesterdayRevenue,
            'pending_orders' => $pendingOrders,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'abandoned_carts' => $abandonedCarts,
        ];

        // ==================== RECENT ORDERS ====================
        $recentOrders = DB::table('orders')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'orders.id',
                'orders.order_number',
                'customers.name as customer_name',
                'orders.grand_total as amount',
                'orders.status',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_number,
                    'customer' => $order->customer_name ?? 'Guest',
                    'amount' => $order->amount,
                    'status' => $order->status,
                    'date' => Carbon::parse($order->created_at)->format('M d, Y'),
                    'time' => Carbon::parse($order->created_at)->format('h:i A'),
                ];
            })
            ->toArray();

        // ==================== TOP PRODUCTS ====================
        $topProducts = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereNull('products.deleted_at')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sales'),
                DB::raw('AVG(order_items.unit_price) as avg_price'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sales' => $product->total_sales,
                    'price' => $product->avg_price,
                    'revenue' => $product->total_revenue,
                ];
            })
            ->toArray();

        // ==================== REVENUE CHART DATA ====================
        // Last 7 days revenue
        $revenueChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayRevenue = DB::table('orders')
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date)
                ->sum('grand_total');

            $revenueChartData['labels'][] = $date->format('D');
            $revenueChartData['data'][] = $dayRevenue ?? 0;
        }

        // Monthly revenue for the current year
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthStart = Carbon::create(date('Y'), $i, 1)->startOfMonth();
            $monthEnd = Carbon::create(date('Y'), $i, 1)->endOfMonth();

            $monthRevenue = DB::table('orders')
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('grand_total');

            $monthlyRevenue['labels'][] = Carbon::create()->month($i)->format('M');
            $monthlyRevenue['data'][] = $monthRevenue ?? 0;
        }

        // ==================== ORDER STATUS DISTRIBUTION ====================
        $orderStatusDistribution = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            })
            ->toArray();

        // ==================== TOP CATEGORIES ====================
        $topCategories = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', 'category_product.category_id', '=', 'categories.id')
            ->whereNull('products.deleted_at')
            ->whereNull('categories.deleted_at')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->toArray();

        // ==================== RECENT CUSTOMERS ====================
        $recentCustomers = DB::table('customers')
            ->select('id', 'name', 'email', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'joined' => Carbon::parse($customer->created_at)->format('M d, Y'),
                    'avatar' => substr($customer->name, 0, 1),
                ];
            })
            ->toArray();

        // ==================== SALES BY PAYMENT METHOD ====================
        $salesByPaymentMethod = DB::table('orders')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(grand_total) as total_amount')
            )
            ->where('status', '!=', 'cancelled')
            ->groupBy('payment_method')
            ->orderBy('total_amount', 'desc')
            ->get()
            ->map(function ($row) {
                return [
                    'name' => strtoupper($row->payment_method),
                    'order_count' => $row->order_count,
                    'total_amount' => $row->total_amount,
                ];
            })
            ->toArray();

        return view('admin.dashboard.index')
            ->with('stats', $stats)
            ->with('recentOrders', $recentOrders)
            ->with('topProducts', $topProducts)
            ->with('recentCustomers', $recentCustomers)
            ->with('revenueChartData', $revenueChartData)
            ->with('monthlyRevenue', $monthlyRevenue)
            ->with('orderStatusDistribution', $orderStatusDistribution)
            ->with('topCategories', $topCategories)
            ->with('salesByPaymentMethod', $salesByPaymentMethod);
    }

    public function getChartData(Request $request)
    {
        // Handle Chart Data Request
        if ($request->has('chart')) {
            $type = $request->get('chart');

            if ($type === 'category') {
                $categories = DB::table('order_items')
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->join('products', 'product_variants.product_id', '=', 'products.id')
                    ->join('category_product', 'products.id', '=', 'category_product.product_id')
                    ->join('categories', 'category_product.category_id', '=', 'categories.id')
                    ->whereNull('products.deleted_at')
                    ->whereNull('categories.deleted_at')
                    ->select(
                        'categories.name',
                        DB::raw('SUM(order_items.total) as total_revenue')
                    )
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', '!=', 'cancelled')
                    ->groupBy('categories.id', 'categories.name')
                    ->orderBy('total_revenue', 'desc')
                    ->limit(8)
                    ->get();

                return response()->json([
                    'categories' => $categories
                ]);
            }
        }

        // Handle Update Request (e.g. Abandoned Carts)
        if ($request->has('update')) {
            $type = $request->get('update');
            
            if ($type === 'carts') {
                 $abandonedCarts = DB::table('carts')
                    ->where('status', 'active')
                    ->where('updated_at', '<', Carbon::now()->subHours(24))
                    ->count();

                return response()->json([
                    'abandoned_carts' => $abandonedCarts
                ]);
            }
        }

        return response()->json([]);
    }
}
