@extends('customer.layouts.master')

@section('title', 'My Account - ' . config('app.name'))

@section('styles')
<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    /* Order status colors */
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
<!-- Success Message -->
@if(session('success_message'))
<div class="fixed top-4 right-4 bg-green-100 text-green-800 px-6 py-3 rounded-full shadow-lg animate-slide-in z-50">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success_message') }}
</div>
@endif

<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600">My Account</li>
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
                        <h3 class="font-bold text-gray-800">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $customer->email ?? $customer->mobile }}</p>
                        <p class="text-xs text-gray-500 mt-1">Member since {{ $customer->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('customer.account.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('customer.wishlist.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-heart"></i>
                        <span>My Wishlist</span>
                        @if($wishlistCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $wishlistCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.orders') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
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
            <!-- Dashboard -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Wishlist Items</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $wishlistCount }}</p>
                            </div>
                            <i class="fas fa-heart text-2xl text-amber-600"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Cart Items</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $cartCount }}</p>
                            </div>
                            <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Orders</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $ordersCount }}</p>
                            </div>
                            <i class="fas fa-shopping-bag text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="mt-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Orders</h3>
                    @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                        <div class="flex items-center justify-between p-4 bg-amber-50 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-amber-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Order #{{ $order['order_number'] }}</h4>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-sm text-gray-600">{{ $order['created_at']->format('M d, Y') }}</span>
                                        <span class="text-sm status-{{ $order['status'] }} px-2 py-1 rounded-full">
                                            {{ ucfirst($order['status']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-700">₹{{ number_format($order['grand_total'], 2) }}</p>
                                <a href="{{ route('customer.account.orders.details', $order['id']) }}"
                                   class="text-sm text-amber-600 hover:text-amber-800">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('customer.account.orders') }}"
                           class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                            View all {{ $ordersCount }} orders →
                        </a>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">You haven't placed any orders yet.</p>
                        <a href="{{ route('customer.home.index') }}" class="inline-block mt-4 text-amber-600 hover:text-amber-800">
                            Start Shopping →
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Account Information -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Account Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $customer->name }}</span>
                            </div>
                            @if($customer->email)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $customer->email }}</span>
                            </div>
                            @endif
                            @if($customer->mobile)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $customer->mobile }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Member Since:</span>
                                <span class="font-medium">{{ $customer->created_at->format('F d, Y') }}</span>
                            </div>
                            @if($customer->last_login_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Login:</span>
                                <span class="font-medium">{{ $customer->last_login_at->diffForHumans() }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-amber-50 p-6 rounded-xl">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-3">

                            <a href="{{ route('customer.account.change-password') }}" class="flex items-center gap-3 text-amber-600 hover:text-amber-800">
                                <i class="fas fa-lock"></i>
                                <span>Change Password</span>
                            </a>
                            <a href="{{ route('customer.account.addresses') }}" class="flex items-center gap-3 text-amber-600 hover:text-amber-800">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Manage Addresses</span>
                            </a>
                            <a href="{{ route('customer.wishlist.index') }}" class="flex items-center gap-3 text-amber-600 hover:text-amber-800">
                                <i class="fas fa-heart"></i>
                                <span>View Wishlist</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Format price function
function formatPrice(price) {
    return '₹' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>
@endsection
