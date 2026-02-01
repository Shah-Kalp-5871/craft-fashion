@extends('customer.layouts.master')

@section('title', 'My Profile | Craft Fashion')
@section('description', 'Manage your profile, orders, and addresses')

@section('content')
<!-- Page Header -->
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold font-playfair text-dark mb-2">My Account</h1>
                <nav class="flex text-sm text-secondary">
                    <a href="{{ route('customer.home.index') }}" class="hover:text-primary transition-colors">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-dark font-medium">Profile</span>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="p-6 bg-primary/5 border-b border-gray-100 text-center">
                        <div class="w-20 h-20 bg-white rounded-full mx-auto shadow-sm flex items-center justify-center mb-3">
                            <span class="text-3xl font-bold text-primary">{{ substr($customer->name, 0, 1) }}</span>
                        </div>
                        <h3 class="font-bold text-dark text-lg truncate">{{ $customer->name }}</h3>
                        <p class="text-secondary text-sm truncate">{{ $customer->email }}</p>
                    </div>
                    
                    <nav class="p-2 space-y-1">
                        <a href="{{ route('customer.account.profile') }}" class="flex items-center px-4 py-3 bg-primary/5 text-primary font-medium rounded-xl transition-colors">
                            <i class="fas fa-user w-6"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('customer.account.orders') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-shopping-bag w-6"></i>
                            <span>My Orders</span>
                        </a>
                        <a href="{{ route('customer.account.addresses') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-map-marker-alt w-6"></i>
                            <span>Addresses</span>
                        </a>
                        <a href="{{ route('customer.wishlist.index') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-heart w-6"></i>
                            <span>Wishlist</span>
                        </a>
                        <a href="{{ route('customer.forgot-password') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-lock w-6"></i>
                            <span>Forgot Password</span>
                        </a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="border-t border-gray-100 mt-2 pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-red-500 hover:bg-red-50 font-medium rounded-xl transition-colors">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="w-full lg:w-3/4">
                
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-primary/10 to-transparent rounded-3xl p-8 mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-dark mb-2">Hello, {{ explode(' ', $customer->name)[0] }}!</h2>
                        <p class="text-secondary">From your dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.</p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fas fa-smile text-6xl text-primary/20"></i>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Orders -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <span class="text-xs font-semibold bg-gray-100 text-gray-500 px-2 py-1 rounded-lg">Lifetime</span>
                        </div>
                        <h3 class="text-3xl font-bold text-dark mb-1">{{ $ordersCount }}</h3>
                        <p class="text-secondary text-sm">Total Orders</p>
                    </div>

                    <!-- Wishlist -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-red-50 text-primary rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-heart"></i>
                            </div>
                            <span class="text-xs font-semibold bg-gray-100 text-gray-500 px-2 py-1 rounded-lg">Saved</span>
                        </div>
                        <h3 class="text-3xl font-bold text-dark mb-1">{{ $wishlistCount }}</h3>
                        <p class="text-secondary text-sm">Wishlist Items</p>
                    </div>

                    <!-- Addresses -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <a href="{{ route('customer.account.addresses') }}" class="text-xs font-semibold text-primary hover:underline">Manage</a>
                        </div>
                        <h3 class="text-3xl font-bold text-dark mb-1">{{ \App\Models\CustomerAddress::where('customer_id', $customer->id)->count() }}</h3>
                        <p class="text-secondary text-sm">Saved Addresses</p>
                    </div>
                </div>

                <!-- Profile Information & Recent Orders Split -->
                <div class="grid grid-cols-1 gap-8">
                    
                    <!-- Profile Information -->
                    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-lg text-dark">Profile Information</h3>
                            <button class="text-primary text-sm font-medium hover:underline cursor-not-allowed opacity-50" title="Edit functionality coming soon">Edit</button>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                <div>
                                    <label class="block text-xs font-medium text-secondary uppercase tracking-wider mb-1">Full Name</label>
                                    <p class="text-dark font-medium">{{ $customer->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-secondary uppercase tracking-wider mb-1">Email Address</label>
                                    <p class="text-dark font-medium">{{ $customer->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-secondary uppercase tracking-wider mb-1">Phone Number</label>
                                    <p class="text-dark font-medium">{{ $customer->mobile ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-secondary uppercase tracking-wider mb-1">Member Since</label>
                                    <p class="text-dark font-medium">{{ $customer->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-lg text-dark">Recent Orders</h3>
                            <a href="{{ route('customer.account.orders') }}" class="text-primary text-sm font-medium hover:underline">View All</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs text-secondary border-b border-gray-100 bg-gray-50/30">
                                        <th class="px-6 py-4 font-semibold uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-4 font-semibold uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-4 font-semibold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 font-semibold uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($recentOrders as $order)
                                    <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-primary">#{{ $order['order_number'] }}</td>
                                        <td class="px-6 py-4 text-secondary">{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'processing' => 'bg-blue-100 text-blue-700',
                                                    'shipped' => 'bg-purple-100 text-purple-700',
                                                    'delivered' => 'bg-green-100 text-green-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                ];
                                                $statusClass = $statusClasses[$order['status']] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                {{ ucfirst($order['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-dark">₹{{ number_format($order['grand_total'], 2) }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('customer.account.orders.details', $order['id']) }}" class="text-primary hover:text-dark font-medium transition-colors">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-secondary">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                                                <p>No orders found</p>
                                                <a href="{{ route('customer.products.list') }}" class="mt-2 text-primary font-medium hover:underline">Start Shopping</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
