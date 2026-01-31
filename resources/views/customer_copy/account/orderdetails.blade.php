@extends('customer.layouts.master')

@section('title', 'Order Details - ' . config('app.name'))

@section('styles')
<style>
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-processing {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-shipped {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-delivered {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-refunded {
        background-color: #f3e8ff;
        color: #6b21a8;
    }

    .status-returned {
        background-color: #fce7f3;
        color: #9d174d;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('customer.account.profile') }}" class="text-amber-600 hover:text-amber-800">My Account</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('customer.account.orders') }}" class="text-amber-600 hover:text-amber-800">My Orders</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600">Order #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                    @php
                        $customer = Auth::guard('customer')->user();
                    @endphp
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-amber-700"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $customer->email ?? $customer->mobile }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('customer.account.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('customer.wishlist.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-heart"></i>
                        <span>My Wishlist</span>
                        @php
                            $wishlistCount = \App\Models\Wishlist::where('customer_id', $customer->id)->count();
                        @endphp
                        @if($wishlistCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $wishlistCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.orders') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                        @php
                            $ordersCount = \App\Models\Order::where('customer_id', $customer->id)->count();
                        @endphp
                        @if($ordersCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $ordersCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.addresses') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Addresses</span>
                    </a>

                    <a href="{{ route('customer.account.change-password') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>

                    <form method="POST" action="{{ route('customer.logout') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 w-full">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Order Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-8 border-b">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h2>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="text-sm text-gray-600">
                                <i class="far fa-calendar mr-1"></i>
                                {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                            </span>
                            <span class="text-sm status-{{ $order->status }} px-3 py-1 rounded-full">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-amber-700">₹{{ number_format($order->grand_total, 2) }}</p>
                        <p class="text-sm text-gray-600 mt-1">Total Amount</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- Order Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Order Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="font-medium">{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Status:</span>
                                    <span class="font-medium {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping Method:</span>
                                    <span class="font-medium">{{ ucfirst($order->shipping_method) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Currency:</span>
                                    <span class="font-medium">{{ $order->currency }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Delivery Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping Status:</span>
                                    <span class="font-medium">{{ ucfirst($order->shipping_status) }}</span>
                                </div>
                                @if($order->shipped_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipped At:</span>
                                    <span class="font-medium">{{ $order->shipped_at->format('M d, Y') }}</span>
                                </div>
                                @endif
                                @if($order->delivered_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Delivered At:</span>
                                    <span class="font-medium">{{ $order->delivered_at->format('M d, Y') }}</span>
                                </div>
                                @endif
                                @if(in_array($order->status, ['processing', 'shipped']) && !$order->delivered_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Est. Delivery:</span>
                                    <span class="font-medium">{{ $order->created_at->addDays(7)->format('M d, Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Order Items ({{ $order->items->count() }})</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-6 bg-amber-50 rounded-2xl">
                                <div class="flex items-start gap-4 mb-4 md:mb-0">
                                    <div class="w-24 h-24 bg-white rounded-lg flex items-center justify-center">
                                        <i class="fas fa-gem text-2xl text-amber-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ $item->product_name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">SKU: {{ $item->sku }}</p>
                                        @if($item->attributes)
                                            @php
                                                $attributes = json_decode($item->attributes, true);
                                            @endphp
                                            @if(is_array($attributes) && count($attributes) > 0)
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach($attributes as $key => $value)
                                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                    {{ $key }}: {{ $value }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif
                                        @endif
                                        <p class="text-amber-700 font-bold text-lg mt-2">₹{{ number_format($item->unit_price, 2) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-xl font-bold text-gray-800 mt-2">₹{{ number_format($item->total, 2) }}</p>
                                    @if($item->discount_amount > 0)
                                    <p class="text-sm text-green-600">Discount: ₹{{ number_format($item->discount_amount, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Shipping Address -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Shipping Address</h3>
                            @if($shippingAddress)
                            <div class="bg-gray-50 p-6 rounded-2xl">
                                <p class="text-gray-800 font-medium">{{ $shippingAddress['name'] ?? 'N/A' }}</p>
                                <p class="text-gray-600 mt-2">
                                    {{ $shippingAddress['address'] ?? 'N/A' }}<br>
                                    {{ $shippingAddress['city'] ?? 'N/A' }}, {{ $shippingAddress['state'] ?? 'N/A' }} {{ $shippingAddress['pincode'] ?? '' }}<br>
                                    {{ $shippingAddress['country'] ?? 'India' }}
                                </p>
                                @if(isset($shippingAddress['phone']))
                                <div class="mt-4 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-2"></i> {{ $shippingAddress['phone'] }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="bg-gray-50 p-6 rounded-2xl">
                                <p class="text-gray-600">No shipping address available</p>
                            </div>
                            @endif
                        </div>

                        <!-- Order Calculation -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h3>
                            <div class="bg-gray-50 p-6 rounded-2xl">
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal ({{ $order->items->count() }} items):</span>
                                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping:</span>
                                        <span class="{{ $order->shipping_total == 0 ? 'text-green-600' : '' }}">
                                            {{ $order->shipping_total == 0 ? 'FREE' : '₹' . number_format($order->shipping_total, 2) }}
                                        </span>
                                    </div>
                                    @if($order->tax_total > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tax:</span>
                                        <span>₹{{ number_format($order->tax_total, 2) }}</span>
                                    </div>
                                    @endif
                                    @if($order->discount_total > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Discount:</span>
                                        <span class="text-green-600">-₹{{ number_format($order->discount_total, 2) }}</span>
                                    </div>
                                    @endif
                                    @if($order->loyalty_points_used > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Loyalty Points Used:</span>
                                        <span class="text-green-600">-₹{{ number_format($order->loyalty_points_used, 2) }}</span>
                                    </div>
                                    @endif
                                    <div class="border-t border-gray-300 pt-3 mt-3">
                                        <div class="flex justify-between font-bold text-lg">
                                            <span>Total:</span>
                                            <span class="text-amber-700">₹{{ number_format($order->grand_total, 2) }}</span>
                                        </div>
                                    </div>
                                    @if($order->loyalty_points_earned > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Loyalty Points Earned:</span>
                                        <span>+{{ number_format($order->loyalty_points_earned, 2) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Order Timeline</h3>
                        <div class="space-y-4">
                            @php
                                $statuses = [
                                    'pending' => ['icon' => 'fas fa-clock', 'color' => 'bg-gray-100', 'text-color' => 'text-gray-400'],
                                    'confirmed' => ['icon' => 'fas fa-check-circle', 'color' => 'bg-blue-100', 'text-color' => 'text-blue-600'],
                                    'processing' => ['icon' => 'fas fa-cog', 'color' => 'bg-amber-100', 'text-color' => 'text-amber-600'],
                                    'shipped' => ['icon' => 'fas fa-truck', 'color' => 'bg-blue-100', 'text-color' => 'text-blue-600'],
                                    'delivered' => ['icon' => 'fas fa-home', 'color' => 'bg-green-100', 'text-color' => 'text-green-600'],
                                    'cancelled' => ['icon' => 'fas fa-times-circle', 'color' => 'bg-red-100', 'text-color' => 'text-red-600'],
                                    'refunded' => ['icon' => 'fas fa-undo-alt', 'color' => 'bg-purple-100', 'text-color' => 'text-purple-600'],
                                    'returned' => ['icon' => 'fas fa-exchange-alt', 'color' => 'bg-pink-100', 'text-color' => 'text-pink-600'],
                                ];

                                $timelineStatuses = [];
                                foreach ($statusHistory as $history) {
                                    $timelineStatuses[$history->status] = $history;
                                }

                                // Add current status if not in history
                                if (!isset($timelineStatuses[$order->status])) {
                                    $timelineStatuses[$order->status] = (object) [
                                        'status' => $order->status,
                                        'created_at' => $order->updated_at,
                                        'notes' => null
                                    ];
                                }

                                // Define order of statuses
                                $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'returned'];
                            @endphp

                            @foreach($statusOrder as $status)
                                @if(isset($timelineStatuses[$status]))
                                    @php
                                        $history = $timelineStatuses[$status];
                                        $isActive = array_search($order->status, $statusOrder) >= array_search($status, $statusOrder);
                                        $colorClass = $isActive ? $statuses[$status]['color'] : 'bg-gray-100';
                                        $textColorClass = $isActive ? $statuses[$status]['text-color'] : 'text-gray-400';
                                    @endphp
                                    <div class="flex items-start gap-4">
                                        <div class="w-8 h-8 {{ $colorClass }} {{ $textColorClass }} rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                            <i class="{{ $statuses[$status]['icon'] }}"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">{{ ucfirst($history->status) }}</p>
                                            <p class="text-sm text-gray-600">{{ $history->created_at->format('M d, Y \a\t h:i A') }}</p>
                                            @if($history->notes)
                                            <p class="text-xs text-gray-500 mt-1">{{ $history->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="flex flex-wrap gap-4 pt-8 border-t border-gray-200">
                        @if(in_array($order->status, ['pending', 'confirmed']))
                        <button onclick="showCancelModal()"
                                class="px-6 py-3 border border-red-600 text-red-600 rounded-lg hover:bg-red-50">
                            <i class="fas fa-times mr-2"></i>Cancel Order
                        </button>
                        @endif

                        <button onclick="downloadInvoice()"
                                class="px-6 py-3 border border-gray-600 text-gray-600 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-file-invoice mr-2"></i>Download Invoice
                        </button>

                        <button onclick="printOrder()"
                                class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                            <i class="fas fa-print mr-2"></i>Print Order
                        </button>

                        @if($order->status == 'delivered' && $order->delivered_at && $order->delivered_at->diffInDays(now()) <= 7)
                        <button onclick="requestReturn()"
                                class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
                            <i class="fas fa-undo-alt mr-2"></i>Request Return
                        </button>
                        @endif

                        <a href="{{ route('customer.home.index') }}"
                           class="px-6 py-3 border border-amber-600 text-amber-600 rounded-lg hover:bg-amber-50 ml-auto">
                            <i class="fas fa-redo mr-2"></i>Buy Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full">
        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Cancel Order</h3>
            <form method="POST" action="#">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Cancellation Reason *</label>
                    <select name="cancellation_reason" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none" required>
                        <option value="">Select a reason</option>
                        <option value="Changed mind">Changed mind</option>
                        <option value="Found better price">Found better price</option>
                        <option value="Delivery time too long">Delivery time too long</option>
                        <option value="Ordered by mistake">Ordered by mistake</option>
                        <option value="Other">Other reason</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeCancelModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex-1">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 flex-1">
                        Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}

function downloadInvoice() {
    alert('Invoice is being generated. It will download shortly.');
    // In real implementation, redirect to invoice download route
    window.location.href = "#";
}

function printOrder() {
    window.print();
}

function requestReturn() {
    alert('Return request has been submitted. Our team will contact you shortly.');
    // In real implementation, submit return request via AJAX
}
</script>
@endsection
