@extends('admin.layouts.master')

@section('title', 'Dashboard - eCommerce Admin')

@section('content')

    <div class="mb-6 lg:mb-8">
        <h2 class="text-xl lg:text-2xl font-bold text-gray-800 mb-2">Dashboard Overview</h2>
        <p class="text-sm lg:text-base text-gray-600">
            Welcome back, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}!
            Here's what's happening today.
        </p>
    </div>

    <!-- =============================
            QUICK STATS ROW
        ============================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Today's Orders -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Today's Orders</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['today_orders']) }}</p>
                    <p class="text-xs lg:text-sm text-gray-500 mt-1">
                        @if($stats['yesterday_orders'] > 0)
                            @php $change = (($stats['today_orders'] - $stats['yesterday_orders']) / $stats['yesterday_orders']) * 100 @endphp
                            <span class="{{ $change >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <i class="fas fa-arrow-{{ $change >= 0 ? 'up' : 'down' }} mr-1"></i>
                                {{ abs(round($change, 1)) }}%
                            </span>
                            from yesterday
                        @else
                            <span class="text-emerald-600">
                                <i class="fas fa-arrow-up mr-1"></i>
                                100%
                            </span>
                            from yesterday
                        @endif
                    </p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white text-base lg:text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Today's Revenue</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($stats['today_revenue'], 2) }}</p>
                    <p class="text-xs lg:text-sm text-gray-500 mt-1">
                        @if($stats['yesterday_revenue'] > 0)
                            @php $change = (($stats['today_revenue'] - $stats['yesterday_revenue']) / $stats['yesterday_revenue']) * 100 @endphp
                            <span class="{{ $change >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <i class="fas fa-arrow-{{ $change >= 0 ? 'up' : 'down' }} mr-1"></i>
                                {{ abs(round($change, 1)) }}%
                            </span>
                            from yesterday
                        @else
                            <span class="text-emerald-600">
                                <i class="fas fa-arrow-up mr-1"></i>
                                100%
                            </span>
                            from yesterday
                        @endif
                    </p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <i class="fas fa-rupee-sign text-white text-base lg:text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending_orders']) }}</p>
                    <p class="text-xs lg:text-sm text-emerald-600 mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        Needs attention
                    </p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-amber-400 to-amber-600 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-base lg:text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['low_stock_products']) }}</p>
                    <p class="text-xs lg:text-sm text-rose-600 mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        {{ $stats['out_of_stock_products'] }} out of stock
                    </p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-rose-400 to-rose-600 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-white text-base lg:text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- =============================
            MAIN STAT CARDS
        ============================= -->
    @php
        $cards = [
            [
                'title' => 'Total Revenue',
                'value' => '₹' . number_format($stats['total_revenue'], 2),
                'change' => $stats['revenue_change'],
                'icon' => 'fas fa-rupee-sign',
                'color' => 'from-emerald-400 to-emerald-600',
                'change_color' => $stats['revenue_change'] >= 0 ? 'text-emerald-600' : 'text-rose-600',
                'period' => 'This month',
            ],
            [
                'title' => 'Total Orders',
                'value' => number_format($stats['total_orders']),
                'change' => $stats['orders_change'],
                'icon' => 'fas fa-shopping-cart',
                'color' => 'from-blue-400 to-blue-600',
                'change_color' => $stats['orders_change'] >= 0 ? 'text-emerald-600' : 'text-rose-600',
                'period' => 'This month',
            ],
            [
                'title' => 'Total Products',
                'value' => number_format($stats['total_products']),
                'change' => $stats['products_change'],
                'icon' => 'fas fa-box',
                'color' => 'from-purple-400 to-purple-600',
                'change_color' => $stats['products_change'] >= 0 ? 'text-emerald-600' : 'text-rose-600',
                'period' => 'New this month',
            ],
            [
                'title' => 'Total Customers',
                'value' => number_format($stats['total_customers']),
                'change' => $stats['customers_change'],
                'icon' => 'fas fa-users',
                'color' => 'from-amber-400 to-amber-600',
                'change_color' => $stats['customers_change'] >= 0 ? 'text-emerald-600' : 'text-rose-600',
                'period' => 'New this month',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        @foreach ($cards as $item)
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs lg:text-sm font-medium text-gray-600 truncate">{{ $item['title'] }}</p>
                        <p class="text-xl lg:text-2xl font-bold text-gray-900 mt-1 truncate">{{ $item['value'] }}</p>
                        <div class="flex items-center mt-1">
                            <p class="text-xs lg:text-sm {{ $item['change_color'] }} mr-2 whitespace-nowrap">
                                <i class="fas fa-arrow-{{ $item['change'] >= 0 ? 'up' : 'down' }} mr-1"></i>
                                {{ abs($item['change']) }}%
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ $item['period'] }}</p>
                        </div>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r {{ $item['color'] }} rounded-lg lg:rounded-xl flex items-center justify-center ml-3 flex-shrink-0">
                        <i class="{{ $item['icon'] }} text-white text-base lg:text-lg"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- =============================
            CHARTS & GRAPHS
        ============================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">

        <!-- Revenue Trend -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 lg:mb-6 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Revenue Trend</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-600 weekly-btn">
                        Weekly
                    </button>
                    <button class="px-3 py-1 text-xs font-medium rounded-lg text-gray-500 hover:bg-gray-100 monthly-btn">
                        Monthly
                    </button>
                </div>
            </div>
            <div class="h-64 lg:h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Order Distribution -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 lg:mb-6 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Order Distribution</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-600 status-btn">
                        By Status
                    </button>
                    <button class="px-3 py-1 text-xs font-medium rounded-lg text-gray-500 hover:bg-gray-100 category-btn">
                        By Category
                    </button>
                </div>
            </div>
            <div class="h-64 lg:h-80">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- =============================
            RECENT ORDERS + TOP PRODUCTS
        ============================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 lg:mb-6 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium whitespace-nowrap">View All</a>
            </div>

            <div class="space-y-3 lg:space-y-4">
                @foreach ($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 lg:p-4 bg-gray-50 rounded-lg lg:rounded-xl hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3 lg:space-x-4 min-w-0 flex-1">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shopping-bag text-indigo-600 text-sm lg:text-base"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate text-sm lg:text-base">{{ $order['id'] }}</p>
                                <div class="flex flex-col xs:flex-row xs:items-center xs:space-x-2 text-xs lg:text-sm text-gray-500 mt-1">
                                    <span class="truncate">{{ $order['customer'] }}</span>
                                    <span class="hidden xs:inline">•</span>
                                    <span>₹{{ number_format($order['amount'], 2) }}</span>
                                    <span class="hidden xs:inline">•</span>
                                    <span>{{ $order['date'] }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="status-badge status-{{ $order['status'] }} capitalize px-2 lg:px-3 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0">
                            {{ $order['status'] }}
                        </span>
                    </div>
                @endforeach
                @if(empty($recentOrders))
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-shopping-cart text-2xl lg:text-3xl mb-2"></i>
                        <p class="text-sm lg:text-base">No recent orders</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 lg:mb-6 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Top Products</h3>
                <a href="{{ route('admin.products.index') }}"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium whitespace-nowrap">View All</a>
            </div>

            <div class="space-y-3 lg:space-y-4">
                @foreach ($topProducts as $product)
                    <div class="flex items-center justify-between p-3 lg:p-4 bg-gray-50 rounded-lg lg:rounded-xl hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3 lg:space-x-4 min-w-0 flex-1">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-purple-100 to-purple-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-box text-purple-600 text-sm lg:text-base"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate text-sm lg:text-base">{{ $product['name'] }}</p>
                                <div class="flex flex-col xs:flex-row xs:items-center xs:space-x-2 lg:space-x-4 text-xs lg:text-sm text-gray-500 mt-1">
                                    <span>{{ $product['sales'] }} sales</span>
                                    <span class="hidden xs:inline">•</span>
                                    <span>₹{{ number_format($product['revenue'], 2) }} revenue</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-emerald-600 font-medium text-sm lg:text-base whitespace-nowrap ml-2">₹{{ number_format($product['price'], 2) }}</span>
                    </div>
                @endforeach
                @if(empty($topProducts))
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box text-2xl lg:text-3xl mb-2"></i>
                        <p class="text-sm lg:text-base">No products sold yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- =============================
            ADDITIONAL SECTIONS
        ============================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-6 lg:mb-8">
        <!-- Recent Customers -->
        <div class="lg:col-span-2 bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 lg:mb-6 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Recent Customers</h3>
                <a href="{{ route('admin.users.index') }}"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium whitespace-nowrap">View All</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4">
                @foreach ($recentCustomers as $customer)
                    <div class="flex items-center p-3 lg:p-4 bg-gray-50 rounded-lg lg:rounded-xl hover:bg-gray-100 transition">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-amber-400 to-amber-600 rounded-full flex items-center justify-center text-white font-bold text-sm lg:text-base mr-3 lg:mr-4 flex-shrink-0">
                            {{ $customer['avatar'] }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-gray-900 truncate text-sm lg:text-base">{{ $customer['name'] }}</p>
                            <p class="text-xs lg:text-sm text-gray-500 truncate">{{ $customer['email'] }}</p>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap ml-2">{{ $customer['joined'] }}</span>
                    </div>
                @endforeach
                @if(empty($recentCustomers))
                    <div class="col-span-1 sm:col-span-2 text-center py-8 text-gray-500">
                        <i class="fas fa-users text-2xl lg:text-3xl mb-2"></i>
                        <p class="text-sm lg:text-base">No recent customers</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sales by Payment Method -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <div class="flex justify-between items-center mb-4 lg:mb-6">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Sales by Payment</h3>
            </div>

            <div class="space-y-3 lg:space-y-4">
                @foreach ($salesByPaymentMethod as $method)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="w-6 h-6 lg:w-8 lg:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3 flex-shrink-0">
                                <i class="fas fa-credit-card text-blue-600 text-xs lg:text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700 text-sm lg:text-base truncate">{{ $method['name'] }}</span>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <p class="font-bold text-gray-900 text-sm lg:text-base">₹{{ number_format($method['total_amount'], 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $method['order_count'] }} orders</p>
                        </div>
                    </div>
                @endforeach
                @if(empty($salesByPaymentMethod))
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-credit-card text-2xl lg:text-3xl mb-2"></i>
                        <p class="text-sm lg:text-base">No payment data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-processing {
        background-color: #e0e7ff;
        color: #3730a3;
    }

    .status-shipped {
        background-color: #f0f9ff;
        color: #0369a1;
    }

    .status-delivered {
        background-color: #f0fdf4;
        color: #166534;
    }

    .status-cancelled {
        background-color: #fef2f2;
        color: #991b1b;
    }

    .status-refunded {
        background-color: #f5f3ff;
        color: #5b21b6;
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Custom breakpoint for extra small screens */
    @media (min-width: 475px) {
        .xs\:inline {
            display: inline !important;
        }
        .xs\:hidden {
            display: none !important;
        }
        .xs\:flex-row {
            flex-direction: row !important;
        }
        .xs\:items-center {
            align-items: center !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        let revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($revenueChartData['labels'] ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenueChartData['data'] ?? []),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `Revenue: ₹${context.raw.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Order Distribution Chart
        const orderCtx = document.getElementById('orderChart').getContext('2d');
        let orderChart = new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($orderStatusDistribution ?? [])),
                datasets: [{
                    data: @json(array_values($orderStatusDistribution ?? [])),
                    backgroundColor: [
                        '#f59e0b', // pending - amber
                        '#3b82f6', // confirmed - blue
                        '#8b5cf6', // processing - purple
                        '#0ea5e9', // shipped - sky
                        '#10b981', // delivered - emerald
                        '#ef4444', // cancelled - red
                        '#6366f1', // refunded - indigo
                        '#64748b'  // returned - slate
                    ],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: window.innerWidth < 1024 ? 'bottom' : 'right',
                        labels: {
                            boxWidth: 10,
                            padding: 15,
                            font: {
                                size: window.innerWidth < 1024 ? 10 : 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} orders (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Chart button interactions
        const weeklyBtn = document.querySelector('.weekly-btn');
        const monthlyBtn = document.querySelector('.monthly-btn');
        const statusBtn = document.querySelector('.status-btn');
        const categoryBtn = document.querySelector('.category-btn');

        // Revenue chart toggle
        weeklyBtn?.addEventListener('click', function() {
            this.classList.add('bg-indigo-50', 'text-indigo-600');
            monthlyBtn?.classList.remove('bg-indigo-50', 'text-indigo-600');

            // Update to weekly data
            revenueChart.data.labels = @json($revenueChartData['labels'] ?? []);
            revenueChart.data.datasets[0].data = @json($revenueChartData['data'] ?? []);
            revenueChart.update();
        });

        monthlyBtn?.addEventListener('click', function() {
            this.classList.add('bg-indigo-50', 'text-indigo-600');
            weeklyBtn?.classList.remove('bg-indigo-50', 'text-indigo-600');

            // Update to monthly data
            revenueChart.data.labels = @json($monthlyRevenue['labels'] ?? []);
            revenueChart.data.datasets[0].data = @json($monthlyRevenue['data'] ?? []);
            revenueChart.update();
        });

        // Order chart toggle - Status view (default)
        statusBtn?.addEventListener('click', function() {
            this.classList.add('bg-indigo-50', 'text-indigo-600');
            categoryBtn?.classList.remove('bg-indigo-50', 'text-indigo-600');

            // Update to status distribution
            orderChart.data.labels = @json(array_keys($orderStatusDistribution ?? []));
            orderChart.data.datasets[0].data = @json(array_values($orderStatusDistribution ?? []));
            orderChart.update();
        });

        categoryBtn?.addEventListener('click', async function() {
            this.classList.add('bg-indigo-50', 'text-indigo-600');
            statusBtn?.classList.remove('bg-indigo-50', 'text-indigo-600');

            try {
                // Fetch category data via AJAX
                const response = await fetch('{{ route("admin.dashboard.data") }}?chart=category', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.categories) {
                    const labels = data.categories.map(c => c.name);
                    const values = data.categories.map(c => c.total_revenue);

                    orderChart.data.labels = labels;
                    orderChart.data.datasets[0].data = values;
                    orderChart.update();
                }
            } catch (error) {
                console.error('Error fetching category data:', error);
            }
        });

        // Handle window resize for chart responsiveness
        window.addEventListener('resize', function() {
            // Update legend position based on screen size
            if (orderChart) {
                const isMobile = window.innerWidth < 1024;
                orderChart.options.plugins.legend.position = isMobile ? 'bottom' : 'right';
                orderChart.options.plugins.legend.labels.font.size = isMobile ? 10 : 11;
                orderChart.update();
            }
        });

        // Update abandoned carts count in real-time (every 5 minutes)
        function updateAbandonedCarts() {
            fetch('{{ route("admin.dashboard.data") }}?update=carts', {
                headers: { 'Accept': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.abandoned_carts !== undefined) {
                        console.log('Abandoned carts updated:', data.abandoned_carts);
                    }
                })
                .catch(error => console.error('Error updating carts:', error));
        }

        // Update every 5 minutes
        setInterval(updateAbandonedCarts, 5 * 60 * 1000);
    });
</script>
@endpush
