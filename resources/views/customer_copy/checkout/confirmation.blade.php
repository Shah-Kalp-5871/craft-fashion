@extends('customer.layouts.master')

@section('title', 'Order Confirmation - ' . config('app.name'))

@section('content')
<!-- ============================================
   ORDER SUCCESS SECTION
   ============================================ -->
<section class="relative bg-gradient-to-b from-amber-50 to-beige-100 py-20 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-green-300/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 relative z-10">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="w-32 h-32 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="fas fa-check-circle text-green-600 text-6xl"></i>
            </div>
            
            <h1 class="brand-title text-5xl md:text-6xl text-gray-800 mb-4">Order Confirmed!</h1>
            <p class="text-xl text-gray-600 mb-8">Thank you for your purchase. Your order has been successfully placed.</p>
        </div>
        
        <!-- Order Details Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Order Details</h2>
                <p class="text-gray-600">Order ID: <span class="font-bold text-amber-700 text-xl">{{ $order->order_id ?? session('order_id') }}</span></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-amber-50 rounded-2xl p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date</span>
                            <span class="font-medium">{{ $order->created_at->format('F d, Y') ?? now()->format('F d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estimated Delivery</span>
                            <span class="font-medium">{{ now()->addDays(7)->format('F d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method</span>
                            <span class="font-medium">{{ $order->payment_method ?? 'Credit Card' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Status</span>
                            <span class="font-medium text-green-600">Processing</span>
                        </div>
                    </div>
                </div>
                
                <!-- Next Steps -->
                <div class="bg-green-50 rounded-2xl p-6">
                    <h3 class="font-bold text-gray-800 mb-4">What's Next?</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-envelope text-green-600 mt-1"></i>
                            <span>You will receive a confirmation email shortly</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-shipping-fast text-green-600 mt-1"></i>
                            <span>Your order will be processed within 24 hours</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-truck text-green-600 mt-1"></i>
                            <span>You'll receive tracking information once shipped</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-headset text-green-600 mt-1"></i>
                            <span>Need help? Contact our support team</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="text-center space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto">
                <a href="{{ route('customer.account.orders') }}" 
                   class="bg-amber-600 text-white px-8 py-4 rounded-full font-bold hover:bg-amber-700 transition-colors inline-flex items-center justify-center gap-3">
                    <i class="fas fa-shopping-bag"></i>
                    View Order Details
                </a>
                <a href="{{ route('customer.home.index') }}" 
                   class="border-2 border-amber-600 text-amber-600 px-8 py-4 rounded-full font-bold hover:bg-amber-50 transition-colors inline-flex items-center justify-center gap-3">
                    <i class="fas fa-gem"></i>
                    Continue Shopping
                </a>
            </div>
            
            <p class="text-gray-600 text-sm mt-6">
                <i class="fas fa-info-circle mr-2"></i>
                A copy of your invoice has been sent to your email address
            </p>
        </div>
    </div>
</section>
@endsection