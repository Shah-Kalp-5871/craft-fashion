@extends('customer.layouts.master')

@section('title', 'Shopping Cart - APIQO Jewellery')
@section('meta_description',
    'Your shopping cart at APIQO Jewellery. Review your selected jewelry items before
    checkout.')

@section('styles')
    <style>

        .animate-slide-in {
            animation: slideInRight 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slide-up {
            animation: slideInUp 0.3s ease-out;
        }

        .animate-pulse {
            animation: pulse 1s ease-in-out;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.1);
        }

        .cart-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #d97706 #f1f1f1;
        }

        .cart-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .cart-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .cart-scrollbar::-webkit-scrollbar-thumb {
            background: #d97706;
            border-radius: 3px;
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }
    </style>
@endsection

@section('content')
    <!-- Cart Hero Section -->
    <section class="relative bg-gradient-to-b from-amber-50 to-beige-100 py-12 md:py-20 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-amber-200/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-56 h-56 md:w-80 md:h-80 bg-amber-300/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Breadcrumb -->
            <div class="mb-6 md:mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('customer.home.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">
                                <i class="fas fa-home mr-2"></i> Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Shopping Cart</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Hero Content -->
            <div class="text-center">
                <div class="inline-flex items-center gap-3 mb-4 md:mb-6">
                    <div class="w-12 h-1 md:w-16 md:h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent">
                    </div>
                    <span class="text-xs md:text-sm font-semibold tracking-widest text-amber-700">YOUR ORDER</span>
                    <div class="w-12 h-1 md:w-16 md:h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent">
                    </div>
                </div>
                <h1 class="brand-title text-3xl md:text-5xl lg:text-7xl text-gray-800 mb-4 md:mb-6">Shopping Cart</h1>
                <p class="text-base md:text-xl text-gray-600 max-w-3xl mx-auto mb-6 md:mb-8" id="cartStatus">
                    @if (count($cart['items'] ?? []) > 0)
                        You have {{ $cart['items_count'] }} item{{ $cart['items_count'] > 1 ? 's' : '' }} in your cart
                    @else
                        Your cart is empty. Let's add some beautiful jewelry!
                    @endif
                </p>
            </div>
        </div>
    </section>

    <!-- Cart Content Section -->
    <section class="py-12 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if (count($cart['items'] ?? []) === 0)
                <!-- Empty Cart -->
                <div class="text-center py-12 md:py-20">
                    <div class="mb-6 md:mb-8">
                        <i class="fas fa-shopping-cart text-gray-300 text-5xl md:text-7xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4">Your cart is empty</h3>
                    <p class="text-gray-600 mb-6 md:mb-8">Looks like you haven't added any items to your cart yet.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('customer.products.list') }}"
                            class="inline-flex items-center gap-2 bg-amber-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-amber-700 transition-colors">
                            <i class="fas fa-gem"></i> Start Shopping
                        </a>
                        <a href="{{ route('customer.home.index') }}"
                            class="inline-flex items-center gap-2 bg-white border border-amber-600 text-amber-600 px-6 py-3 rounded-full font-semibold hover:bg-amber-50 transition-colors">
                            <i class="fas fa-star"></i> View Featured
                        </a>
                    </div>
                </div>
            @else
                <!-- Cart with items -->
                <div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Cart Items -->
                        <div class="lg:col-span-2">
                            <!-- Cart Header -->
                            <div
                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                                    Your Items (<span id="itemCount"
                                        class="text-amber-600">{{ $cart['items_count'] }}</span>)
                                </h2>
                                <div class="flex items-center gap-4">
                                    <button onclick="clearCart()"
                                        class="text-sm text-red-600 hover:text-red-800 flex items-center gap-2 transition-colors">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </button>
                                    <a href="{{ url()->current() }}"
                                        class="text-sm text-amber-600 hover:text-amber-800 flex items-center gap-2 transition-colors">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </a>
                                </div>
                            </div>

                            <!-- Cart Items Container -->
                            <div class="space-y-4 md:space-y-6 max-h-[600px] overflow-y-auto cart-scrollbar pr-2"
                                id="cartItemsContainer">
                                @foreach ($cart['items'] as $item)
                                    @php
                                        $unitPrice =
                                            $item['quantity'] > 0
                                                ? $item['total'] / $item['quantity']
                                                : $item['unit_price'] ?? 0;
                                        $isInStock = ($item['stock_quantity'] ?? 0) > 0;
                                        $maxStock = $item['stock_quantity'] ?? 0;
                                    @endphp
                                    <div class="cart-item bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-6 border border-gray-100 hover:border-amber-200 transition-all duration-300"
                                        id="cartItem-{{ $item['id'] }}">
                                        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                                            <!-- Product Image -->
                                            <div class="w-full md:w-32 h-32 md:h-32 flex-shrink-0">
                                                <img src="{{ !empty($item['image']) ? asset('storage/' . $item['image']) : asset('images/placeholder-product.jpg') }}"
                                                    alt="{{ $item['product_name'] }}"
                                                    class="w-full h-full object-cover rounded-lg md:rounded-xl">
                                            </div>
                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col h-full">
                                                    <div class="flex-1">
                                                        <div class="mb-2 md:mb-3">
                                                            <h3
                                                                class="font-bold text-gray-800 text-base md:text-lg mb-1 line-clamp-2">
                                                                {{ $item['product_name'] }}</h3>
                                                            <p class="text-xs md:text-sm text-gray-500">SKU:
                                                                {{ $item['sku'] ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-3 md:gap-4 mb-3 md:mb-4">
                                                            <span
                                                                class="text-xl md:text-2xl font-bold text-amber-700">₹{{ number_format($unitPrice, 2) }}</span>
                                                            <span
                                                                class="text-xs md:text-sm {{ $isInStock ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} px-2 md:px-3 py-1 rounded-full">
                                                                <i
                                                                    class="fas {{ $isInStock ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                                                {{ $isInStock ? 'In Stock' : 'Out of Stock' }}
                                                            </span>
                                                        </div>
                                                        @if (!empty($item['attributes']))
                                                            <div class="mb-3 md:mb-4">
                                                                @foreach ($item['attributes'] as $key => $value)
                                                                    <span
                                                                        class="inline-block text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded mr-2 mb-2">{{ $key }}:
                                                                        {{ $value }}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="flex items-center justify-between pt-3 md:pt-4 border-t border-gray-100">
                                                        <!-- Quantity Controls -->
                                                        <div class="flex items-center gap-4">
                                                            <div
                                                                class="flex items-center border border-gray-300 rounded-full">
                                                                <button onclick="changeQuantity('{{ $item['id'] }}', -1)"
                                                                    class="quantity-btn minus-btn w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 hover:text-amber-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                                    <i class="fas fa-minus text-xs md:text-sm"></i>
                                                                </button>
                                                                <span id="quantity-{{ $item['id'] }}"
                                                                    data-stock="{{ $maxStock }}"
                                                                    class="w-8 md:w-12 text-center font-semibold text-sm md:text-base">
                                                                    {{ $item['quantity'] }}
                                                                </span>
                                                                <button onclick="changeQuantity('{{ $item['id'] }}', 1)"
                                                                    class="quantity-btn plus-btn w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-gray-600 hover:text-amber-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                                    {{ !$isInStock || $item['quantity'] >= $maxStock ? 'disabled' : '' }}>
                                                                    <i class="fas fa-plus text-xs md:text-sm"></i>
                                                                </button>
                                                            </div>
                                                            <!-- Remove Button -->
                                                            <button onclick="removeCartItem('{{ $item['id'] }}')"
                                                                class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors">
                                                                <i class="fas fa-times text-xs md:text-sm"></i>
                                                            </button>
                                                        </div>
                                                        <!-- Subtotal -->
                                                        <div class="text-right">
                                                            <p class="text-xs text-gray-600">Subtotal:</p>
                                                            <p class="text-lg md:text-xl font-bold text-gray-800"
                                                                id="subtotal-{{ $item['id'] }}">
                                                                ₹{{ number_format($item['total'], 2) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Continue Shopping & Proceed -->
                            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <a href="{{ route('customer.products.list') }}"
                                    class="inline-flex items-center gap-3 text-amber-700 hover:text-amber-800 font-semibold transition-colors">
                                    <i class="fas fa-arrow-left"></i> Continue Shopping
                                </a>

                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-24">
                                <div
                                    class="bg-gradient-to-b from-amber-50 to-amber-100 rounded-2xl md:rounded-3xl shadow-xl p-6 md:p-8 border border-amber-200">
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>
                                    <div class="space-y-3 md:space-y-4 mb-6">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span class="font-semibold text-gray-800"
                                                id="orderSubtotal">₹{{ number_format($cart['subtotal'], 2) }}</span>
                                        </div>
                                        {{-- Shipping Removed --}}
                                        <div id="taxBreakdownContainer">
                                            @if(!empty($cart['tax_breakdown']))
                                                @foreach($cart['tax_breakdown'] as $tax)
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-gray-600 text-sm">{{ $tax['name'] }}</span>
                                                        <span class="font-semibold text-gray-800 text-sm">₹{{ number_format($tax['amount'], 2) }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="flex justify-between items-center">
                                                    <span class="text-gray-600">Tax</span>
                                                    <span class="font-semibold text-gray-800" id="orderTax">₹{{ number_format($cart['tax_total'], 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if (isset($cart['discount_total']) && $cart['discount_total'] > 0)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Discount</span>
                                                <span class="font-semibold text-green-600"
                                                    id="orderDiscount">-₹{{ number_format($cart['discount_total'], 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-6">
                                        <!-- Available Promo Codes -->
                                        <div id="availablePromoCodes" class="mb-4"></div>
                                        
                                        <div class="relative">
                                            <input type="text" id="promoCode" placeholder="Enter promo code"
                                                class="w-full px-4 py-3 pl-10 rounded-full border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors">
                                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2"><i
                                                    class="fas fa-tag text-amber-600"></i></div>
                                            <button onclick="applyPromoCode()"
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-amber-600 text-white px-4 py-1.5 rounded-full text-sm hover:bg-amber-700 transition-colors">Apply</button>
                                        </div>
                                        <div id="promoMessage" class="text-sm mt-2 hidden"></div>
                                    </div>
                                    <div class="border-t border-amber-300 pt-4 md:pt-6 mb-6">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg md:text-xl font-bold text-gray-800">Total</span>
                                            <span class="text-2xl md:text-3xl font-bold text-amber-700"
                                                id="totalAmount">₹{{ number_format($cart['grand_total'], 2) }}</span>
                                        </div>
                                        <p class="text-xs md:text-sm text-gray-600 mt-2"><i
                                                class="fas fa-info-circle mr-1"></i> Shipping & taxes calculated at checkout</p>
                                    </div>
                                    @if (session('customer_logged_in'))
                                        <!-- User is logged in - Direct checkout -->
                                        <a href="{{ route('customer.checkout.index') }}"
                                            class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 md:py-4 rounded-full font-bold text-base md:text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 mb-4 flex items-center justify-center">
                                            <i class="fas fa-lock mr-2"></i> Proceed to Checkout
                                        </a>
                                    @else
                                        <!-- User is not logged in - Show modal trigger -->
                                        <button type="button" onclick="showLoginModal()"
                                            class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 md:py-4 rounded-full font-bold text-base md:text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 mb-4 flex items-center justify-center">
                                            <i class="fas fa-lock mr-2"></i> Proceed to Checkout
                                        </button>
                                    @endif


                                    <!-- Security Badges (Always shown) -->
                                    <div class="flex justify-center gap-4 mt-6">
                                        <div class="text-center"><i
                                                class="fas fa-shield-alt text-green-600 text-lg md:text-xl mb-1 md:mb-2"></i>
                                            <p class="text-xs text-gray-600">Secure</p>
                                        </div>
                                        <div class="text-center"><i
                                                class="fas fa-lock text-blue-600 text-lg md:text-xl mb-1 md:mb-2"></i>
                                            <p class="text-xs text-gray-600">SSL</p>
                                        </div>
                                        <div class="text-center"><i
                                                class="fas fa-certificate text-purple-600 text-lg md:text-xl mb-1 md:mb-2"></i>
                                            <p class="text-xs text-gray-600">Guaranteed</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 space-y-3 md:space-y-4">
                                    <div class="flex items-start gap-3 text-sm text-gray-600"><i
                                            class="fas fa-sync-alt text-amber-600 mt-0.5 flex-shrink-0"></i><span>30-day
                                            return policy. Easy returns & exchanges.</span></div>
                                    <div class="flex items-start gap-3 text-sm text-gray-600"><i
                                            class="fas fa-headset text-amber-600 mt-0.5 flex-shrink-0"></i><span>Need help?
                                            Call us at +91 1800-123-4567</span></div>
                                    <div class="flex items-start gap-3 text-sm text-gray-600"><i
                                            class="fas fa-shield-alt text-amber-600 mt-0.5 flex-shrink-0"></i><span>Secure
                                            payment with 256-bit SSL encryption</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Recommended Products Section -->
    @if (isset($recommendedProducts) && count($recommendedProducts) > 0)
        <section class="py-12 md:py-20 bg-gradient-to-b from-white to-amber-50">
            <div class="max-w-7xl mx-auto px-4">
                <div>
                    <div class="text-center mb-8 md:mb-12">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 md:mb-4">You Might Also Like</h2>
                        <p class="text-gray-600">Complete your look with these perfect matches</p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach ($recommendedProducts as $product)
                            <div
                                class="product-card bg-white rounded-lg md:rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 group">
                                <a href="{{ route('customer.products.show', $product['slug'] ?? $product['id']) }}"
                                    class="block">
                                    <div class="relative aspect-square overflow-hidden">
                                        <img src="{{ !empty($product['main_image']) ? asset('storage/' . $product['main_image']) : asset('images/placeholder-product.jpg') }}"
                                            alt="{{ $product['name'] }}"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    </div>
                                    <div class="p-3 md:p-4">
                                        <h3 class="font-semibold text-gray-800 text-sm md:text-base mb-2 line-clamp-2">
                                            {{ $product['name'] }}</h3>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1 md:gap-2">
                                                <p class="text-base md:text-lg font-bold text-gray-900">
                                                    ₹{{ number_format($product['price'], 0) }}</p>
                                                @if (isset($product['compare_price']) && $product['compare_price'] > $product['price'])
                                                    <p class="text-xs md:text-sm text-gray-400 line-through">
                                                        ₹{{ number_format($product['compare_price'], 0) }}</p>
                                                @endif
                                            </div>
                                            <button
                                                onclick="event.preventDefault(); addToCartFromRecommendation({{ $product['id'] }}, event)"
                                                class="bg-amber-600 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center hover:bg-amber-700 transition-colors">
                                                <i class="fas fa-cart-plus text-xs md:text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
@push('scripts')
    <script>
        // Login Modal Functions
       function showLoginModal() {
    const modal = document.getElementById('loginModal');
    const modalContent = document.getElementById('modalContent');

    modal.classList.remove('hidden');

    requestAnimationFrame(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    });

    document.body.style.overflow = 'hidden';
}


        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            const modalContent = document.getElementById('modalContent');

            // Animate out
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Restore body scroll
                document.body.style.overflow = 'auto';
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('loginModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('loginModal').classList.contains('hidden')) {
                closeLoginModal();
            }
        });

        // Auto-show modal if checkout URL was accessed directly without login
        @if (request()->has('require_login'))
            <
            script >
                document.addEventListener('DOMContentLoaded', function() {
                    showLoginModal();
                });
    </script>
    @endif
    </script>
@endpush

@push('scripts')
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Configure Axios for API calls
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';

        // Load available promo codes on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAvailablePromoCodes();
            
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('animate-slide-up');
            });
        });

        // Load available promo codes
        async function loadAvailablePromoCodes() {
            try {
                const response = await axios.get('/api/customer/offers/active');
                console.log('Offers API Response:', response.data);
                
                if (response.data.success && response.data.data.length > 0) {
                    const offers = response.data.data;
                    const container = document.getElementById('availablePromoCodes');
                    
                    let html = '<div class="mb-3"><p class="text-xs font-semibold text-gray-700 mb-2">Available Offers:</p><div class="space-y-2">';
                    
                    offers.forEach(offer => {
                        let discount = '';
                        if (offer.offer_type === 'percentage') {
                            discount = `${offer.discount_value}% OFF`;
                        } else if (offer.offer_type === 'fixed') {
                            discount = `₹${offer.discount_value} OFF`;
                        } else if (offer.offer_type === 'bogo') {
                            discount = `Buy ${offer.buy_qty} Get ${offer.get_qty}`;
                        } else if (offer.offer_type === 'buy_x_get_y') {
                            discount = `Buy ${offer.buy_qty} Get ${offer.get_qty}`;
                        } else if (offer.offer_type === 'free_shipping') {
                            discount = 'FREE SHIPPING';
                        } else {
                            discount = 'SPECIAL OFFER';
                        }
                        
                        html += `
                            <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded-lg cursor-pointer hover:bg-green-100 transition-colors" 
                                 onclick="applyPromoCodeDirect('${offer.code}')">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tag text-green-600 text-sm"></i>
                                    <div>
                                        <p class="text-xs font-bold text-green-800">${offer.code}</p>
                                        <p class="text-xs text-green-600">${offer.name}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-green-700">${discount}</span>
                            </div>
                        `;
                    });
                    
                    html += '</div></div>';
                    container.innerHTML = html;
                } else {
                    console.log('No offers available or API returned empty data');
                }
            } catch (error) {
                console.error('Failed to load promo codes:', error);
                console.error('Error details:', error.response);
            }
        }

        // Apply promo code directly (on click)
        function applyPromoCodeDirect(code) {
            document.getElementById('promoCode').value = code;
            applyPromoCode();
        }

        // Apply promo code
        async function applyPromoCode() {
            const code = document.getElementById('promoCode').value.trim();
            
            if (!code) {
                showNotification('Please enter a coupon code', 'error');
                return;
            }

            try {
                const response = await axios.post('/cart/apply-coupon', {
                    coupon_code: code
                });

                if (response.data.success) {
                    showNotification(response.data.message, 'success');
                    location.reload();
                }
            } catch (error) {
                showNotification(error.response?.data?.message || 'Failed to apply coupon', 'error');
            }
        }

        // Remove coupon
        async function removeCoupon() {
            try {
                const response = await axios.post('/cart/remove-coupon');
                
                if (response.data.success) {
                    showNotification(response.data.message, 'success');
                    location.reload();
                }
            } catch (error) {
                showNotification('Failed to remove coupon', 'error');
            }
        }

        // Change quantity function
        function changeQuantity(itemId, delta) {
            const qtyEl = document.getElementById(`quantity-${itemId}`);
            const currentQty = parseInt(qtyEl.textContent);
            const stock = parseInt(qtyEl.dataset.stock);
            const newQty = currentQty + delta;

            // Guard rules
            if (newQty < 1) {
                removeCartItem(itemId);
                return;
            }

            if (newQty > stock) {
                showNotification('Stock limit reached', 'error');
                return;
            }

            updateItemQuantity(itemId, newQty);
        }

        // Update item quantity (API call)
        async function updateItemQuantity(itemId, newQuantity) {
            // Disable buttons during request
            const buttons = document.querySelectorAll(`#cartItem-${itemId} .quantity-btn`);
            buttons.forEach(btn => btn.disabled = true);

            try {
                const response = await axios.post(`/cart/update/${itemId}`, {
                    quantity: newQuantity,
                    _method: 'PUT'
                });

                if (response.data.success) {
                    const data = response.data.data;
                    const cart = data.cart;

                    // Find the updated item
                    const item = cart.items.find(i => i.id == itemId);
                    if (!item) return;

                    // Update quantity display
                    const qtyEl = document.getElementById(`quantity-${itemId}`);
                    qtyEl.textContent = item.quantity;

                    // Update subtotal display
                    document.getElementById(`subtotal-${itemId}`).textContent = `${parseFloat(item.total).toFixed(2)}`;

                    // Update header cart count (using your global function)
                    updateCartCount(cart.items_count);

                    // Update item count in cart header
                    const itemCountElement = document.getElementById('itemCount');
                    if (itemCountElement) {
                        itemCountElement.textContent = cart.items_count;
                    }

                    // Update cart status message
                    const cartStatus = document.getElementById('cartStatus');
                    if (cartStatus) {
                        cartStatus.textContent =
                            `You have ${cart.items_count} item${cart.items_count > 1 ? 's' : ''} in your cart`;
                    }

                    // Update order summary
                    updateOrderSummary(cart);

                    // Update button states
                    const plusButton = document.querySelector(`#cartItem-${itemId} .plus-btn`);
                    const minusButton = document.querySelector(`#cartItem-${itemId} .minus-btn`);
                    const stock = parseInt(qtyEl.dataset.stock);

                    if (plusButton) plusButton.disabled = item.quantity >= stock;
                    if (minusButton) minusButton.disabled = item.quantity <= 1;

                    showNotification('Quantity updated successfully!', 'success');
                }
            } catch (error) {
                console.error('Failed to update quantity:', error);
                const errorMessage = error.response?.data?.message || 'Failed to update quantity';
                showNotification(errorMessage, 'error');
            } finally {
                // Re-enable buttons
                buttons.forEach(btn => btn.disabled = false);
            }
        }

        // Remove item from cart (API call)
        async function removeCartItem(itemId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            try {
                const response = await axios.delete(`/cart/remove/${itemId}`);

                if (response.data.success) {
                    const data = response.data.data;
                    const itemElement = document.getElementById(`cartItem-${itemId}`);

                    if (itemElement) {
                        // Add fade-out animation
                        itemElement.style.opacity = '0';
                        itemElement.style.transform = 'translateX(-20px)';
                        itemElement.style.transition = 'all 0.3s ease';

                        setTimeout(() => {
                            itemElement.remove();

                            // If no items left, reload page to show empty cart
                            const remainingItems = document.querySelectorAll('[id^="cartItem-"]').length;
                            if (remainingItems === 0) {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 500);
                            } else {
                                // Update order summary
                                updateOrderSummary(data.cart);

                                // Update header cart count (using your global function)
                                updateCartCount(data.cart.items_count);

                                // Update item count
                                const itemCountElement = document.getElementById('itemCount');
                                if (itemCountElement) {
                                    itemCountElement.textContent = data.cart.items_count;
                                }

                                // Update cart status
                                const cartStatus = document.getElementById('cartStatus');
                                if (cartStatus) {
                                    cartStatus.textContent =
                                        `You have ${data.cart.items_count} item${data.cart.items_count > 1 ? 's' : ''} in your cart`;
                                }
                            }
                        }, 300);
                    }

                    showNotification('Item removed from cart', 'success');
                }
            } catch (error) {
                console.error('Failed to remove item:', error);
                showNotification(error.response?.data?.message || 'Failed to remove item', 'error');
            }
        }

        // Clear entire cart (API call)
        async function clearCart() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            try {
                const response = await axios.delete('/cart/clear');

                if (response.data.success) {
                    showNotification('Cart cleared successfully', 'success');
                    // Update header cart count to 0
                    updateCartCount(0);
                    // Reload page to show empty cart
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                console.error('Failed to clear cart:', error);
                showNotification(error.response?.data?.message || 'Failed to clear cart', 'error');
            }
        }

        // Apply promo code (API call)
        async function applyPromoCode() {
            const promoCode = document.getElementById('promoCode').value.trim();
            const promoMessage = document.getElementById('promoMessage');

            if (!promoCode) {
                promoMessage.className = 'text-sm mt-2 text-red-600';
                promoMessage.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Please enter a promo code';
                promoMessage.classList.remove('hidden');
                return;
            }

            try {
                const response = await axios.post('/cart/apply-coupon', {
                    coupon_code: promoCode
                });

                if (response.data.success) {
                    promoMessage.className = 'text-sm mt-2 text-green-600';
                    promoMessage.innerHTML = `<i class="fas fa-check-circle mr-1"></i> ${response.data.message}`;
                    promoMessage.classList.remove('hidden');

                    // Reload page to show updated totals
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    promoMessage.className = 'text-sm mt-2 text-red-600';
                    promoMessage.innerHTML = `<i class="fas fa-times-circle mr-1"></i> ${response.data.message}`;
                    promoMessage.classList.remove('hidden');
                }
            } catch (error) {
                promoMessage.className = 'text-sm mt-2 text-red-600';
                promoMessage.innerHTML =
                    `<i class="fas fa-times-circle mr-1"></i> ${error.response?.data?.message || 'Failed to apply promo code'}`;
                promoMessage.classList.remove('hidden');
            }
        }

        // Add to cart from recommendation (API call)
        async function addToCartFromRecommendation(productId, event) {
            event.preventDefault();

            try {
                const response = await axios.post('/cart/add', {
                    variant_id: productId,
                    quantity: 1
                });

                if (response.data.success) {
                    showNotification('Product added to cart!', 'success');
                    // Update header cart count
                    updateCartCount(response.data.data.cart_count);
                    // Reload page to show updated cart
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } catch (error) {
                console.error('Failed to add to cart:', error);
                showNotification(error.response?.data?.message || 'Failed to add to cart', 'error');
            }
        }

        // Update order summary (for real-time updates)
        function updateOrderSummary(cartData) {
            // Format currency
            const formatCurrency = (amount) => {
                return `₹${parseFloat(amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            };

            // Update subtotal
            const subtotalElement = document.getElementById('orderSubtotal');
            if (subtotalElement && cartData.subtotal !== undefined) {
                subtotalElement.textContent = formatCurrency(cartData.subtotal);
            }

            // Shipping update removed

            // Update tax breakdown
            const taxContainer = document.getElementById('taxBreakdownContainer');
            if (taxContainer && cartData.tax_breakdown) {
                let taxHtml = '';
                if (cartData.tax_breakdown.length > 0) {
                    cartData.tax_breakdown.forEach(tax => {
                        taxHtml += `
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-600 text-sm">${tax.name}</span>
                                <span class="font-semibold text-gray-800 text-sm">${formatCurrency(tax.amount)}</span>
                            </div>
                        `;
                    });
                } else {
                    taxHtml = `
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-semibold text-gray-800" id="orderTax">${formatCurrency(cartData.tax_total)}</span>
                        </div>
                    `;
                }
                taxContainer.innerHTML = taxHtml;
            } else {
                const taxElement = document.getElementById('orderTax');
                if (taxElement && cartData.tax_total !== undefined) {
                    taxElement.textContent = formatCurrency(cartData.tax_total);
                }
            }

            // Update total
            const totalElement = document.getElementById('totalAmount');
            if (totalElement && cartData.grand_total !== undefined) {
                totalElement.textContent = formatCurrency(cartData.grand_total);
            }

            // Update discount
            const discountElement = document.getElementById('orderDiscount');
            if (discountElement && cartData.discount_total !== undefined) {
                if (cartData.discount_total > 0) {
                    discountElement.textContent = `-₹${parseFloat(cartData.discount_total).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    discountElement.parentElement.classList.remove('hidden');
                } else {
                    discountElement.parentElement.classList.add('hidden');
                }
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.custom-notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `custom-notification fixed top-4 right-4 z-50 ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-6 py-3 rounded-full shadow-lg animate-slide-in`;
            notification.innerHTML =
                `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i> ${message}`;
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add event listener for Enter key in promo code input
        document.getElementById('promoCode')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyPromoCode();
            }
        });
    </script>
@endpush

