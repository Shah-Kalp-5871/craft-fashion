@extends('customer.layouts.master')

@section('title', 'Order Confirmed - ' . config('constants.SITE_NAME'))

@section('content')
<div class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Success Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-dark mb-4 font-playfair">Order Confirmed!</h1>
            <p class="text-lg text-gray-600">Thank you for your purchase. We've received your order and are getting it ready for shipment.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Order Details (Left Column) -->
            <div class="md:col-span-2 space-y-8">
                <!-- Order Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="px-8 py-6 border-b border-gray-50">
                        <h3 class="text-xl font-semibold text-dark">Order Items</h3>
                    </div>
                    <div class="p-8">
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 border-b border-gray-50 last:border-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                                        @php
                                            $image = $item->variant->display_image ?? 'assets/images/placeholder.jpg';
                                        @endphp
                                        <img src="{{ asset($image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-dark">{{ $item->product_name }}</h4>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                        @if($item->attributes)
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($item->attributes as $key => $value)
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-md uppercase">{{ $key }}: {{ $value }}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right font-semibold text-dark">
                                    ₹{{ number_format($item->total, 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Shipping and Payment Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-semibold text-dark mb-4"><i class="fas fa-truck-fast mr-2 text-primary"></i> Shipping Address</h3>
                        <div class="text-gray-600 space-y-1">
                            <p class="font-medium text-dark">{{ $order->shipping_address['name'] ?? 'N/A' }}</p>
                            <p>{{ $order->shipping_address['address'] ?? 'N/A' }}</p>
                            @if(!empty($order->shipping_address['address2']))
                                <p>{{ $order->shipping_address['address2'] }}</p>
                            @endif
                            <p>{{ $order->shipping_address['city'] ?? 'N/A' }}, {{ $order->shipping_address['state'] ?? 'N/A' }}</p>
                            <p>{{ $order->shipping_address['country'] ?? 'India' }} - {{ $order->shipping_address['pincode'] ?? 'N/A' }}</p>
                            <p class="mt-2 text-sm"><i class="fas fa-phone-alt mr-1"></i> {{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-semibold text-dark mb-4"><i class="fas fa-credit-card mr-2 text-primary"></i> Payment Method</h3>
                        <p class="text-gray-600 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                        <div class="mt-6 pt-6 border-t border-gray-50">
                            <h3 class="text-lg font-semibold text-dark mb-4"><i class="fas fa-info-circle mr-2 text-primary"></i> Order Status</h3>
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase 
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status === 'completed') bg-green-100 text-green-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary (Right Column) -->
            <div class="space-y-8" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="text-xl font-semibold text-dark">Order Summary</h3>
                        <p class="text-sm text-gray-500">{{ $order->order_number }}</p>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span>₹{{ number_format($order->shipping_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax</span>
                            <span>₹{{ number_format($order->tax_total, 2) }}</span>
                        </div>
                        @if($order->discount_total > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>-₹{{ number_format($order->discount_total, 2) }}</span>
                        </div>
                        @endif
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-lg font-bold text-dark">Grand Total</span>
                            <span class="text-2xl font-bold text-primary">₹{{ number_format($order->grand_total, 2) }}</span>
                        </div>
                        
                        <div class="pt-8 space-y-3">
                            <a href="{{ route('customer.products.list') }}" class="block w-full text-center px-6 py-4 bg-primary text-white rounded-2xl font-bold hover:bg-dark transition duration-300 shadow-md">
                                Continue Shopping
                            </a>
                            <a href="{{ route('customer.account.orders') }}" class="block w-full text-center px-6 py-4 bg-white border-2 border-primary text-primary rounded-2xl font-bold hover:bg-primary hover:text-white transition duration-300">
                                View My Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: true
        });
    });
</script>
@endpush
