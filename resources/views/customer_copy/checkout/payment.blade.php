@extends('customer.layouts.master')

@section('title', 'Payment - APIQO Jewellery')

@section('content')
<section class="relative bg-gradient-to-b from-amber-50 to-beige-100 py-20 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 bg-amber-200/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-amber-300/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('customer.home.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-amber-600">
                            <i class="fas fa-home mr-2"></i> Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.checkout.process') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-amber-600 md:ml-2">Checkout</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Payment</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="text-center">
            <h1 class="brand-title text-5xl md:text-7xl text-gray-800 mb-6">
                Secure Payment
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Complete your payment securely
            </p>
        </div>
    </div>
</section>

<section class="py-10 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Payment Gateway -->
            <div>
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Complete Payment</h2>

                    <div class="mb-6">
                        <p class="text-gray-600 mb-4">Order #: <span class="font-bold">{{ $order->order_number }}</span></p>
                        <p class="text-gray-600 mb-2">Amount to pay:</p>
                        <p class="text-4xl font-bold text-amber-700">₹{{ number_format($order->grand_total, 2) }}</p>
                    </div>

                    <form id="paymentForm" action="{{ route('customer.checkout.payment.callback') }}" method="POST">
                        @csrf
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <div class="mb-6">
                            <button type="button" id="payButton"
                                    class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-lock mr-2"></i>
                                Pay ₹{{ number_format($order->grand_total, 2) }}
                            </button>
                        </div>

                        <div class="text-center">
                            <img src="{{ asset('images/razorpay-logo.png') }}" alt="Razorpay" class="h-10 mx-auto opacity-70">
                            <p class="text-xs text-gray-500 mt-2">
                                Secured by Razorpay • SSL Encrypted
                            </p>
                        </div>
                    </form>
                </div>

                <div class="bg-amber-50 rounded-2xl p-6 border border-amber-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-shield-alt text-green-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">100% Secure Payment</h4>
                            <p class="text-sm text-gray-600">
                                Your payment is secured with 256-bit SSL encryption. We do not store your card details.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-gradient-to-b from-amber-50 to-amber-100 rounded-3xl shadow-xl p-8 border border-amber-200 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>

                    <div class="mb-6 max-h-64 overflow-y-auto">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-amber-200 last:border-0">
                            <img src="{{ $item->variant->display_image ? asset('storage/' . $item->variant->display_image) : asset('images/placeholder-product.jpg') }}"
                                 alt="{{ $item->product_name }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</h4>
                                @if(!empty($item->attributes))
                                    <p class="text-xs text-gray-500 mt-1">
                                        @foreach($item->attributes as $key => $value)
                                            {{ $key }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </p>
                                @endif
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-amber-700 font-bold">₹{{ number_format($item->total, 2) }}</span>
                                    <span class="text-gray-600">Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold {{ $order->shipping_total == 0 ? 'text-green-600' : 'text-amber-700' }}">
                                {{ $order->shipping_total == 0 ? 'FREE' : '₹' . number_format($order->shipping_total, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (GST 18%)</span>
                            <span class="font-semibold">₹{{ number_format($order->tax_total, 2) }}</span>
                        </div>
                        @if($order->discount_total > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-semibold text-green-600">-₹{{ number_format($order->discount_total, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="border-t border-amber-300 pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">Total</span>
                            <span class="text-3xl font-bold text-amber-700">₹{{ number_format($order->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Delivery Address</h3>
                    <div class="text-gray-600">
                        <p class="font-medium">{{ $order->shipping_address['name'] ?? '' }}</p>
                        <p class="mt-1">{{ $order->shipping_address['address'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['address2']))
                            <p>{{ $order->shipping_address['address2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['pincode'] ?? '' }}</p>
                        <p class="mt-2">
                            <i class="fas fa-phone-alt mr-2"></i>{{ $order->shipping_address['phone'] ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderData = @json($razorpayOrder);
    const order = @json($order);

    if (!orderData.success) {
        showNotification('Payment gateway error: ' + orderData.message, 'error');
        return;
    }

    const options = {
        key: "{{ $keyId }}",
        amount: orderData.amount,
        currency: orderData.currency,
        name: "APIQO Jewellery",
        description: `Order #${order.order_number}`,
        image: "{{ asset('images/logo.png') }}",
        order_id: orderData.order_id,
        handler: function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('paymentForm').submit();
        },
        prefill: {
            name: "{{ $order->shipping_address['name'] ?? '' }}",
            email: "{{ $order->shipping_address['email'] ?? '' }}",
            contact: "{{ $order->shipping_address['phone'] ?? '' }}"
        },
        notes: {
            order_id: order.id,
            order_number: order.order_number
        },
        theme: {
            color: "#D97706"
        },
        modal: {
            ondismiss: function() {
                showNotification('Payment cancelled', 'warning');
            }
        }
    };

    const rzp = new Razorpay(options);

    document.getElementById('payButton').onclick = function(e) {
        rzp.open();
        e.preventDefault();
    };

    // Auto open Razorpay modal on page load (optional)
    // rzp.open();
});

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-full shadow-lg z-50 ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' :
        type === 'error' ? 'bg-red-100 text-red-800 border border-red-300' :
        'bg-yellow-100 text-yellow-800 border border-yellow-300'
    }`;
    notification.innerHTML = `
        <i class="fas ${
            type === 'success' ? 'fa-check-circle' :
            type === 'error' ? 'fa-exclamation-circle' :
            'fa-exclamation-triangle'
        } mr-2"></i>
        ${message}
    `;

    notification.style.animation = 'slideInRight 0.3s ease-out';
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'fadeOut 0.5s ease-out forwards';
        setTimeout(() => notification.remove(), 500);
    }, 5000);
}
</script>
@endsection
