@extends('customer.layouts.master')

@section('title', 'Order #' . $order->order_number . ' | ' . config('constants.SITE_NAME'))

@section('content')
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('customer.home.index') }}" class="inline-flex items-center text-sm font-medium text-secondary hover:text-primary">
                            <i class="fas fa-home mr-2"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.account.profile') }}" class="text-sm font-medium text-secondary hover:text-primary">My Account</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.account.orders') }}" class="text-sm font-medium text-secondary hover:text-primary">Orders</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-dark md:ml-2">Order #{{ $order->order_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold font-playfair text-dark mb-2">Order Details</h1>
                <p class="text-secondary">Order #{{ $order->order_number }} • Placed on {{ $order->created_at->format('F j, Y h:i A') }}</p>
            </div>
            <div class="flex gap-3">
                @if($order->status == 'pending' || $order->status == 'confirmed')
                    <!-- Simple Cancel Button Form -->
                    {{-- Note: Actual cancel implementation would serve a form to the 'customer.account.orders.cancel' route --}}
                    {{-- For now, we'll just show a disabled button or a link if the route is ready --}}
                    {{-- <form action="{{ route('customer.account.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <button type="submit" class="bg-white text-red-500 border border-red-500 px-4 py-2 rounded-full text-sm font-medium hover:bg-red-50 transition">
                            Cancel Order
                        </button>
                    </form> --}}
                @endif
                <a href="{{ route('customer.account.orders.download-invoice', $order->id) }}" class="bg-primary text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-primary/90 transition shadow-sm flex items-center">
                    <i class="fas fa-download mr-2"></i> Download Invoice
                </a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Order Items & Progress -->
            <div class="w-full lg:w-2/3 space-y-8">
                
                <!-- Order Progress -->
                @if(!in_array($order->status, ['cancelled', 'refunded', 'returned']))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h3 class="font-bold text-lg text-dark mb-6">Order Status</h3>
                    <div class="relative">
                        <!-- Progress line -->
                        <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-100 -translate-y-1/2 z-0 rounded-full"></div>
                        @php
                            $progressWidth = 'w-0';
                            if(in_array($order->status, ['pending', 'failed'])) $progressWidth = 'w-[15%]';
                            if(in_array($order->status, ['confirmed', 'processing'])) $progressWidth = 'w-[40%]';
                            if($order->status == 'shipped') $progressWidth = 'w-[65%]';
                            if($order->status == 'delivered') $progressWidth = 'w-[100%]';
                        @endphp
                        <div class="absolute top-1/2 left-0 h-1 bg-primary -translate-y-1/2 z-0 rounded-full transition-all duration-1000 {{ $progressWidth }}"></div>
                        
                        <!-- Steps -->
                        <div class="flex justify-between relative z-10">
                            <!-- Step 1: Ordered -->
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center mb-2 shadow-md ring-4 ring-white">
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                </div>
                                <p class="text-sm font-medium text-dark">Ordered</p>
                                <p class="text-xs text-secondary mt-1">{{ $order->created_at->format('M d') }}</p>
                            </div>
                            
                            <!-- Step 2: Confirmed -->
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full 
                                    {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 
                                         'bg-primary text-white' : 'bg-gray-100 text-gray-400' }} 
                                    flex items-center justify-center mb-2 shadow-md ring-4 ring-white transition-colors duration-500">
                                    <i class="fas fa-check-circle text-sm"></i>
                                </div>
                                <p class="text-sm font-medium {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'text-dark' : 'text-gray-400' }}">Confirmed</p>
                                @if($order->confirmed_at)
                                <p class="text-xs text-secondary mt-1">{{ $order->confirmed_at->format('M d') }}</p>
                                @endif
                            </div>
                            
                            <!-- Step 3: Shipped -->
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full 
                                    {{ in_array($order->status, ['shipped', 'delivered']) ? 
                                         'bg-primary text-white' : 'bg-gray-100 text-gray-400' }} 
                                    flex items-center justify-center mb-2 shadow-md ring-4 ring-white transition-colors duration-500">
                                    <i class="fas fa-truck text-sm"></i>
                                </div>
                                <p class="text-sm font-medium {{ in_array($order->status, ['shipped', 'delivered']) ? 'text-dark' : 'text-gray-400' }}">Shipped</p>
                                @if($order->shipped_at)
                                <p class="text-xs text-secondary mt-1">{{ $order->shipped_at->format('M d') }}</p>
                                @endif
                            </div>
                            
                            <!-- Step 4: Delivered -->
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full 
                                    {{ $order->status == 'delivered' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400' }} 
                                    flex items-center justify-center mb-2 shadow-md ring-4 ring-white transition-colors duration-500">
                                    <i class="fas fa-box-open text-sm"></i>
                                </div>
                                <p class="text-sm font-medium {{ $order->status == 'delivered' ? 'text-dark' : 'text-gray-400' }}">Delivered</p>
                                @if($order->delivered_at)
                                <p class="text-xs text-secondary mt-1">{{ $order->delivered_at->format('M d') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Cancelled/Refunded Status Banner -->
                <div class="bg-red-50 rounded-2xl border border-red-100 p-6 flex flex-col md:flex-row items-center gap-4 text-center md:text-left">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-times text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800 text-lg">Order {{ ucfirst($order->status) }}</h3>
                        <p class="text-red-600 mt-1">
                            This order was {{ $order->status }} on {{ $order->cancelled_at ? $order->cancelled_at->format('F j, Y') : $order->updated_at->format('F j, Y') }}.
                            @if($order->cancellation_reason)
                            <br>Reason: {{ $order->cancellation_reason }}
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-lg text-dark">Items ({{ $order->items->count() }})</h3>
                        <span class="text-sm text-secondary">{{ $order->payment_status == 'paid' ? 'Payment Verified' : 'Payment Pending' }}</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                        <div class="p-6 flex flex-col md:flex-row gap-6 items-start md:items-center hover:bg-gray-50 transition-colors">
                            <!-- Image -->
                            <div class="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                @if($item->variant && $item->variant->display_image)
                                    <img src="{{ asset('storage/' . $item->variant->display_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Details -->
                            <div class="flex-1">
                                <h4 class="font-bold text-dark text-lg mb-1">{{ $item->product_name }}</h4>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @if(!empty($item->attributes))
                                        @foreach($item->attributes as $key => $value)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($key) }}: {{ $value }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-sm text-secondary">
                                    SKU: {{ $item->sku ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <!-- Price & Qty -->
                            <div class="text-left md:text-right">
                                <p class="text-lg font-bold text-primary">₹{{ number_format($item->unit_price, 2) }}</p>
                                <p class="text-sm text-secondary">Qty: {{ $item->quantity }}</p>
                                <p class="text-sm font-medium text-dark mt-1">Total: ₹{{ number_format($item->total, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                 <!-- Status History -->
                 @if($statusHistory->count() > 0)
                 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                     <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-lg text-dark">Order Activity Log</h3>
                     </div>
                     <div class="p-6">
                        <div class="relative pl-4">
                            <!-- Vertical Line -->
                            <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gray-100"></div>

                            <div class="space-y-8 relative">
                                @foreach($statusHistory as $history)
                                <div class="relative flex gap-6 group">
                                    <!-- Icon Step -->
                                    <div class="flex-shrink-0 relative z-10">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white shadow-sm transition-all duration-300 group-hover:scale-110
                                            {{ $loop->last ? 'bg-primary text-white' : 'bg-white text-gray-400 border-gray-100' }}">
                                            @if($history->status == 'ordered' || $history->status == 'pending')
                                                <i class="fas fa-shopping-bag text-sm"></i>
                                            @elseif($history->status == 'confirmed')
                                                <i class="fas fa-check text-sm"></i>
                                            @elseif($history->status == 'processing')
                                                <i class="fas fa-cog fa-spin text-sm"></i>
                                            @elseif($history->status == 'shipped')
                                                <i class="fas fa-truck text-sm"></i>
                                            @elseif($history->status == 'delivered')
                                                <i class="fas fa-box-open text-sm"></i>
                                            @elseif($history->status == 'cancelled')
                                                <i class="fas fa-times text-sm"></i>
                                            @else
                                                <i class="fas fa-circle text-[8px]"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 pt-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 mb-2">
                                            <h4 class="font-bold text-dark text-base capitalize">{{ $history->status }}</h4>
                                            <span class="text-xs font-medium text-secondary bg-gray-50 px-2 py-1 rounded-full border border-gray-100">
                                                {{ $history->created_at->format('M d, Y • h:i A') }}
                                            </span>
                                        </div>
                                        
                                        @if($history->notes)
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 text-sm text-secondary relative">
                                            <div class="absolute top-0 left-4 -mt-1 w-2 h-2 bg-gray-50 border-t border-l border-gray-100 transform rotate-45"></div>
                                            {{ $history->notes }}
                                        </div>
                                        @else
                                        <p class="text-sm text-secondary">Status updated to {{ strtolower($history->status) }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                     </div>
                 </div>
                 @endif
            </div>

            <!-- Right Column: Summary & Addresses -->
            <div class="w-full lg:w-1/3 space-y-8">
                
                <!-- Order Summary -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-lg text-dark mb-6">Order Summary</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between text-secondary">
                            <span>Subtotal</span>
                            <span class="font-medium text-dark">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between text-secondary">
                            <span>Shipping</span>
                            @if($order->shipping_total > 0)
                                <span class="font-medium text-dark">₹{{ number_format($order->shipping_total, 2) }}</span>
                            @else
                                <span class="font-medium text-green-600">Free</span>
                            @endif
                        </div>
                        
                        @if($order->discount_total > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span class="font-medium">-₹{{ number_format($order->discount_total, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($order->tax_total > 0)
                        <div class="flex justify-between text-secondary">
                            <span>Tax</span>
                            <span class="font-medium text-dark">₹{{ number_format($order->tax_total, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="border-t border-dashed border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg text-dark">Grand Total</span>
                                <span class="font-bold text-xl text-primary">₹{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                            <p class="text-xs text-right text-secondary mt-1">Inclusive of all taxes</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="font-semibold text-dark mb-2">Payment Method</h4>
                        <div class="flex items-center text-sm text-secondary">
                            <i class="far fa-credit-card mr-2 text-lg"></i>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-lg text-dark mb-4">Shipping Address</h3>
                    <div class="text-sm text-secondary leading-relaxed space-y-1">
                        @if($shippingAddress)
                            <p class="font-semibold text-dark text-base">{{ $shippingAddress['name'] ?? $order->customer->name }}</p>
                            <p>{{ $shippingAddress['address_line_1'] ?? '' }}</p>
                            @if(!empty($shippingAddress['address_line_2']))
                                <p>{{ $shippingAddress['address_line_2'] }}</p>
                            @endif
                            <p>{{ $shippingAddress['city'] ?? '' }}, {{ $shippingAddress['state'] ?? '' }} {{ $shippingAddress['postal_code'] ?? '' }}</p>
                            <p>{{ $shippingAddress['country'] ?? '' }}</p>
                            <p class="mt-2 text-dark"><i class="fas fa-phone-alt text-xs mr-2 text-gray-400"></i>{{ $shippingAddress['phone'] ?? $order->customer->mobile }}</p>
                        @else
                            <p class="text-gray-400 italic">No shipping address provided.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Billing Address -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-lg text-dark mb-4">Billing Address</h3>
                    <div class="text-sm text-secondary leading-relaxed space-y-1">
                        @if($billingAddress)
                            <p class="font-semibold text-dark text-base">{{ $billingAddress['name'] ?? ($shippingAddress['name'] ?? $order->customer->name) }}</p>
                            <p>{{ $billingAddress['address_line_1'] ?? '' }}</p>
                            @if(!empty($billingAddress['address_line_2']))
                                <p>{{ $billingAddress['address_line_2'] }}</p>
                            @endif
                            <p>{{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }} {{ $billingAddress['postal_code'] ?? '' }}</p>
                            <p>{{ $billingAddress['country'] ?? '' }}</p>
                            <p class="mt-2 text-dark"><i class="fas fa-phone-alt text-xs mr-2 text-gray-400"></i>{{ $billingAddress['phone'] ?? ($shippingAddress['phone'] ?? $order->customer->mobile) }}</p>
                        @else
                            <p class="text-gray-400 italic">Same as shipping address.</p>
                        @endif
                    </div>
                </div>

                <!-- Need Help -->
                <div class="bg-primary/5 rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-white text-primary rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                        <i class="fas fa-headset text-xl"></i>
                    </div>
                    <h4 class="font-bold text-dark mb-2">Need help with this order?</h4>
                    <p class="text-xs text-secondary mb-4">Our support team is available 24/7 to assist you.</p>
                    <a href="{{ config('constants.WHATSAPP_LINK') }}?text=I need help with order #{{ $order->order_number }}" class="text-primary font-medium hover:underline text-sm">Contact Support</a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
