@extends('customer.layouts.master')

@section('title', 'My Orders - ' . config('app.name'))

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
            <li class="text-gray-600">My Orders</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-amber-700"></i>
                    </div>
                    <div>
                        @php
                            $customer = Auth::guard('customer')->user();
                        @endphp
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
                        @if($totalOrders > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $totalOrders }}
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
                <h2 class="text-2xl font-bold text-gray-800 mb-6">My Orders ({{ $totalOrders }})</h2>

                <!-- Order Status Summary -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    @foreach($statusCounts as $status => $count)
                    @if($count > 0)
                    <div class="bg-amber-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-amber-700 mb-1">{{ $count }}</div>
                        <div class="text-sm text-gray-600">{{ ucfirst($status) }}</div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-- Order Filter -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('customer.account.orders') }}"
                           class="px-4 py-2 rounded-full {{ !isset($status) ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All Orders
                        </a>
                        @foreach($statusCounts as $status => $count)
                        @if($count > 0)
                        <a href="{{ route('customer.account.orders.filter', $status) }}"
                           class="px-4 py-2 rounded-full {{ (isset($statusFilter) && $statusFilter == $status) ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ ucfirst($status) }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>

                <!-- Orders List -->
                <div class="space-y-6">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Order Header -->
                            <div class="bg-amber-50 p-6 border-b border-gray-200">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-bold text-gray-800 text-lg">Order #{{ $order->order_number }}</h3>
                                            <span class="text-sm status-{{ $order->status }} px-3 py-1 rounded-full">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span><i class="far fa-calendar mr-1"></i> {{ $order->created_at->format('M d, Y') }}</span>
                                            <span><i class="fas fa-box mr-1"></i> {{ $order->items->count() }} items</span>
                                            @if($order->status == 'shipped' || $order->status == 'delivered')
                                            <span><i class="fas fa-truck mr-1"></i> Shipped</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-amber-700">₹{{ number_format($order->grand_total, 2) }}</p>
                                        @if($order->status == 'pending' || $order->status == 'processing' || $order->status == 'shipped')
                                        <p class="text-sm text-gray-600 mt-1">
                                            @if($order->status == 'shipped')
                                                @if($order->delivered_at)
                                                    Delivered: {{ $order->delivered_at->format('M d, Y') }}
                                                @else
                                                    Est. Delivery: {{ $order->created_at->addDays(5)->format('M d, Y') }}
                                                @endif
                                            @elseif($order->status == 'processing')
                                                Processing
                                            @else
                                                Payment Pending
                                            @endif
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-{{ min($order->items->count(), 3) }} gap-4 mb-6">
                                    @foreach($order->items->take(3) as $item)
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-gem text-amber-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</p>
                                            <p class="text-amber-700 font-bold text-sm">₹{{ number_format($item->unit_price, 2) }}</p>
                                            <p class="text-gray-600 text-xs">Qty: {{ $item->quantity }}</p>
                                            <p class="text-gray-600 text-xs">SKU: {{ $item->sku }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-500">+{{ $order->items->count() - 3 }} more items</span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Order Actions -->
                                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                                    <a href="{{ route('customer.account.orders.details', $order->id) }}"
                                       class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>

                                    @if(in_array($order->status, ['pending', 'confirmed']))
                                    <button onclick="showCancelModal('{{ $order->id }}')"
                                            class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50">
                                        <i class="fas fa-times mr-2"></i>Cancel Order
                                    </button>
                                    @endif

                                    @if($order->status == 'delivered')
                                    <button onclick="downloadInvoice('{{ $order->id }}')"
                                            class="px-4 py-2 border border-gray-600 text-gray-600 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-file-invoice mr-2"></i>Download Invoice
                                    </button>
                                    @endif

                                    @if($order->status == 'delivered' && $order->delivered_at && $order->delivered_at->diffInDays(now()) <= 7)
                                    <button onclick="requestReturn('{{ $order->id }}')"
                                            class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
                                        <i class="fas fa-undo-alt mr-2"></i>Request Return
                                    </button>
                                    @endif

                                    <a href="{{ route('customer.home.index') }}"
                                       class="px-4 py-2 border border-amber-600 text-amber-600 rounded-lg hover:bg-amber-50 ml-auto">
                                        <i class="fas fa-redo mr-2"></i>Buy Again
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-bag text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">No Orders Found</h3>
                        <p class="text-gray-600 mb-6">You haven't placed any orders yet.</p>
                        <a href="{{ route('customer.home.index') }}"
                           class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-600 to-amber-800 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:shadow-xl">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Start Shopping
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Order Summary -->
                @if($orders->count() > 0)
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-amber-50 p-6 rounded-xl">
                        <h4 class="font-bold text-gray-800 mb-4">Order Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Orders</span>
                                <span class="font-bold">{{ $totalOrders }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Spent</span>
                                <span class="font-bold text-amber-700">₹{{ number_format($totalSpent, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Average Order</span>
                                <span class="font-bold">₹{{ number_format($averageOrder, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-xl">
                        <h4 class="font-bold text-gray-800 mb-4">Shipping Address</h4>
                        @php
                            $latestOrder = $orders->first();
                            $address = $latestOrder->shipping_address ? (is_array($latestOrder->shipping_address) ? $latestOrder->shipping_address : json_decode($latestOrder->shipping_address, true)) : null;
                        @endphp
                        @if($address)
                        <p class="text-sm text-gray-600">
                            {{ $address['name'] ?? 'N/A' }}<br>
                            {{ $address['address'] ?? 'N/A' }}<br>
                            {{ $address['city'] ?? 'N/A' }}, {{ $address['state'] ?? 'N/A' }} {{ $address['pincode'] ?? '' }}<br>
                            {{ $address['country'] ?? 'India' }}
                        </p>
                        @else
                        <p class="text-sm text-gray-600">No address available</p>
                        @endif
                    </div>

                    <div class="bg-green-50 p-6 rounded-xl">
                        <h4 class="font-bold text-gray-800 mb-4">Need Help?</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Questions about your order? We're here to help!
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('customer.page.contact') }}" class="flex items-center gap-2 text-amber-600 hover:text-amber-800">
                                <i class="fas fa-headset"></i>
                                <span>Contact Support</span>
                            </a>
                            <a href="{{ route('customer.page.faq') }}" class="flex items-center gap-2 text-amber-600 hover:text-amber-800">
                                <i class="fas fa-question-circle"></i>
                                <span>FAQ</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full">
        <div class="p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Cancel Order</h3>
            <form id="cancelForm" method="POST">
                @csrf
                <input type="hidden" id="orderId" name="order_id">
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
function showCancelModal(orderId) {
    document.getElementById('orderId').value = orderId;
    document.getElementById('cancelForm').action = `/account/orders/${orderId}/cancel`;
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}

function downloadInvoice(orderId) {
    alert(`Invoice for order ${orderId} is being generated. It will download shortly.`);
    // In real implementation, redirect to invoice download route
    window.location.href = `/account/orders/${orderId}/invoice`;
}

function requestReturn(orderId) {
    alert(`Return request for order ${orderId} has been submitted. Our team will contact you shortly.`);
    // In real implementation, submit return request via AJAX
}
</script>
@endsection
