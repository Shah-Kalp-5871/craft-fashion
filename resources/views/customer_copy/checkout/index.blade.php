@extends('customer.layouts.master')

@section('title', 'Checkout - APIQO Jewellery')

@section('content')
    <!-- ============================================
                   CHECKOUT HERO SECTION
                   ============================================ -->
    <section class="relative bg-gradient-to-b from-amber-50 to-beige-100 py-20 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-0 w-96 h-96 bg-amber-200/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-amber-300/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Breadcrumb -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('customer.home.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-amber-600">
                                <i class="fas fa-home mr-2"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('customer.cart') }}"
                                    class="ml-1 text-sm font-medium text-gray-700 hover:text-amber-600 md:ml-2">Cart</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Checkout</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Hero Content -->
            <div class="text-center">
                <div class="inline-flex items-center gap-3 mb-6">
                    <div class="w-16 h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent"></div>
                    <span class="text-sm font-semibold tracking-widest text-amber-700">SECURE CHECKOUT</span>
                    <div class="w-16 h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent"></div>
                </div>

                <h1 class="brand-title text-5xl md:text-7xl text-gray-800 mb-6">
                    Checkout
                </h1>

                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    Complete your order securely in a few simple steps
                </p>
            </div>
        </div>
    </section>

    <!-- ============================================
                   CHECKOUT PROCESS
                   ============================================ -->
    <section class="pb-10 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Checkout Steps -->
            <div class="mb-12">
                <div class="">
                    <div class="flex justify-center md:justify-center min-w-max px-4">
                        <div class="flex items-center gap-4 md:gap-0">

                            <!-- Cart -->
                            <div class="flex flex-col items-center shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-amber-600 text-white rounded-full flex items-center justify-center mb-2">
                                    <i class="fas fa-shopping-cart text-sm md:text-base"></i>
                                </div>
                                <span class="text-xs md:text-sm font-medium text-gray-800">Cart</span>
                            </div>

                            <div class="w-12 md:w-24 h-1 bg-amber-600"></div>

                            <!-- Details -->
                            <div class="flex flex-col items-center shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-amber-600 text-white rounded-full flex items-center justify-center mb-2">
                                    <i class="fas fa-user text-sm md:text-base"></i>
                                </div>
                                <span class="text-xs md:text-sm font-medium text-gray-800">Details</span>
                            </div>

                            <div class="w-12 md:w-24 h-1 bg-amber-600"></div>

                            <!-- Shipping -->
                            <div class="flex flex-col items-center shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center mb-2">
                                    <i class="fas fa-truck text-sm md:text-base"></i>
                                </div>
                                <span class="text-xs md:text-sm font-medium text-gray-800">Shipping</span>
                            </div>

                            <div class="w-12 md:w-24 h-1 bg-gray-300"></div>

                            <!-- Payment -->
                            <div class="flex flex-col items-center shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center mb-2">
                                    <i class="fas fa-credit-card text-sm md:text-base"></i>
                                </div>
                                <span class="text-xs md:text-sm font-medium text-gray-600">Payment</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            @if (count($cart['items'] ?? []) === 0)
                <!-- Empty Cart Message -->
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-shopping-cart text-gray-300 text-6xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Your cart is empty</h3>
                    <p class="text-gray-600 mb-8">Please add items to your cart before proceeding to checkout.</p>
                    <a href="{{ route('customer.products.list') }}"
                        class="inline-flex items-center gap-2 bg-amber-600 text-white px-8 py-3 rounded-full font-bold hover:bg-amber-700 transition-colors">
                        <i class="fas fa-gem"></i>
                        Browse Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-2">
                        <form id="checkoutForm" method="POST" action="{{ route('customer.checkout.process') }}"
                            class="space-y-8">

                            @if ($errors->any())
                                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                                    <ul class="list-disc list-inside text-red-700 text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @csrf

                            <!-- Personal Information -->
                            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6">Personal Information</h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                        <input type="text" name="full_name" required
                                            value="{{ $cart['is_logged_in'] ? auth()->guard('customer')->user()->name ?? '' : '' }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <input type="email" name="email" required
                                            value="{{ $cart['is_logged_in'] ? auth()->guard('customer')->user()->email ?? '' : '' }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                        <input type="tel" name="phone" required
                                            value="{{ $cart['is_logged_in'] ? auth()->guard('customer')->user()->mobile ?? '' : '' }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                        <select name="country" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                            <option value="">Select Country</option>
                                            <option value="IN" selected>India</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6">Shipping Address</h2>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1 *</label>
                                        <input type="text" name="address" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                                        <input type="text" name="address2"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                            <input type="text" name="city" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                            <input type="text" name="state" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">pincode Code
                                                *</label>
                                            <input type="text" name="pincode" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                                        </div>
                                    </div>

                                    <!-- Shipping Method -->
                                    <div class="mt-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-4">Shipping Method</label>
                                        <div class="space-y-3" id="shipping-method-container">
                                            <!-- Dynamic shipping methods container -->

                                            <!-- Dynamic shipping methods will be loaded here -->
                                            <div id="shipping-method-placeholder" class="p-4 border border-dashed border-gray-300 rounded-xl text-center text-gray-500">
                                                <i class="fas fa-truck mb-2 text-xl"></i>
                                                <p>Enter your pincode to check delivery availability and rates</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6">Payment Method</h2>

                                <div class="space-y-3">
                                    <!-- Credit/Debit Card -->
                                    <label
                                        class="flex items-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:border-amber-500">
                                        <input type="radio" name="payment_method" value="online" checked>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">Online Payment</p>
                                            <div class="flex gap-2 mt-2">
                                                <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                                <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                                                <i class="fab fa-cc-amex text-2xl text-blue-800"></i>
                                                <i class="fab fa-cc-discover text-2xl text-orange-600"></i>
                                            </div>
                                        </div>
                                        <i class="fas fa-credit-card text-gray-400 text-xl"></i>
                                    </label>



                                    <!-- Cash on Delivery -->
                                    <!-- Cash on Delivery -->
                                    @if($codAvailable)
                                    <label
                                        class="flex items-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:border-amber-500">
                                        <input type="radio" name="payment_method" value="cod"
                                            class="mr-3 text-amber-600">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">Cash on Delivery</p>
                                            <p class="text-sm text-gray-600">Pay when you receive</p>
                                        </div>
                                        <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                                    </label>
                                    @else
                                    <label
                                        class="flex items-center p-4 border border-gray-200 rounded-xl bg-gray-50 cursor-not-allowed opacity-60">
                                        <input type="radio" name="payment_method" value="cod" disabled
                                            class="mr-3 text-gray-400">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-500">Cash on Delivery</p>
                                            <p class="text-xs text-red-500">Not available for one or more items in your cart</p>
                                        </div>
                                        <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                                    </label>
                                    @endif
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-200">
                                <label class="flex items-start">
                                    <input type="checkbox" id="termsAgree" name="terms_agree" required
                                        class="mt-1 mr-3 text-amber-600">
                                    <span class="text-sm text-gray-600">
                                        I agree to the <a href="/terms" class="text-amber-600 hover:underline">Terms &
                                            Conditions</a> and
                                        <a href="/privacy" class="text-amber-600 hover:underline">Privacy Policy</a>. I
                                        understand that my
                                        personal data will be processed in accordance with applicable laws.
                                    </span>
                                </label>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('customer.cart') }}"
                                    class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-full font-bold hover:bg-gray-50 text-center">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Cart
                                </a>
                                <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-amber-600 to-amber-800 text-white py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-lock mr-2"></i>
                                    <span id="orderButtonText">
                                        Place Order & Pay ₹{{ number_format($cart['grand_total'], 2) }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-24">
                            <div
                                class="bg-gradient-to-b from-amber-50 to-amber-100 rounded-3xl shadow-xl p-8 border border-amber-200">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>

                                <!-- Items List -->
                                <div class="mb-6 max-h-64 overflow-y-auto" id="orderItemsContainer">
                                    @foreach ($cart['items'] as $item)
                                        <div
                                            class="flex items-center gap-3 mb-4 pb-4 border-b border-amber-200 last:border-0">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/placeholder-product.jpg') }}"
                                                alt="{{ $item['product_name'] }}"
                                                class="w-16 h-16 object-cover rounded-lg">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-800 text-sm">{{ $item['product_name'] }}
                                                </h4>
                                                @if (!empty($item['attributes']))
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @foreach ($item['attributes'] as $key => $value)
                                                            {{ $key }}:
                                                            {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </p>
                                                @endif
                                                <div class="flex justify-between items-center mt-1">
                                                    <span
                                                        class="text-amber-700 font-bold">₹{{ number_format($item['total'], 2) }}</span>
                                                    <span class="text-gray-600">Qty: {{ $item['quantity'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Summary Details -->
                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal ({{ $cart['items_count'] }}
                                            item{{ $cart['items_count'] > 1 ? 's' : '' }})</span>
                                        <span class="font-semibold"
                                            id="orderSubtotal">₹{{ number_format($cart['subtotal'], 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping</span>
                                        <span
                                            class="font-semibold {{ $cart['shipping_total'] == 0 ? 'text-green-600' : 'text-amber-700' }}"
                                            id="shippingCostDisplay">
                                            @if ($cart['shipping_total'] == 0)
                                                FREE
                                            @else
                                                ₹{{ number_format($cart['shipping_total'], 2) }}
                                            @endif
                                        </span>
                                    </div>
                                    @if(isset($cart['tax_breakdown']) && count($cart['tax_breakdown']) > 0)
                                        @foreach($cart['tax_breakdown'] as $tax)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">{{ $tax['name'] }} ({{ $tax['rate'] }}%)</span>
                                                <span class="font-semibold text-gray-800">₹{{ number_format($tax['amount'], 2) }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tax</span>
                                            <span class="font-semibold" id="orderTax">₹{{ number_format($cart['tax_total'], 2) }}</span>
                                        </div>
                                    @endif

                                    @if ($cart['discount_total'] > 0)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Discount</span>
                                            <span class="font-semibold text-green-600"
                                                id="orderDiscount">-₹{{ number_format($cart['discount_total'], 2) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Total -->
                                <div class="border-t border-amber-300 pt-6 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-800">Total</span>
                                        <span class="text-3xl font-bold text-amber-700"
                                            id="totalAmount">₹{{ number_format($cart['grand_total'], 2) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Including all taxes and shipping charges
                                    </p>
                                </div>

                                <!-- Security Badges -->
                                <div class="flex justify-center gap-4 mt-6">
                                    <div class="text-center">
                                        <i class="fas fa-shield-alt text-green-600 text-xl mb-2"></i>
                                        <p class="text-xs text-gray-600">Secure Payment</p>
                                    </div>
                                    <div class="text-center">
                                        <i class="fas fa-lock text-blue-600 text-xl mb-2"></i>
                                        <p class="text-xs text-gray-600">SSL Encrypted</p>
                                    </div>
                                    <div class="text-center">
                                        <i class="fas fa-certificate text-purple-600 text-xl mb-2"></i>
                                        <p class="text-xs text-gray-600">Guaranteed</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-6 space-y-4">
                                <div class="flex items-start gap-3 text-sm text-gray-600">
                                    <i class="fas fa-sync-alt text-amber-600 mt-0.5"></i>
                                    <span>30-day return policy. Easy returns & exchanges.</span>
                                </div>
                                <div class="flex items-start gap-3 text-sm text-gray-600">
                                    <i class="fas fa-headset text-amber-600 mt-0.5"></i>
                                    <span>Need help? Call us at +91 1800-123-4567</span>
                                </div>
                                <div class="flex items-start gap-3 text-sm text-gray-600">
                                    <i class="fas fa-gift text-amber-600 mt-0.5"></i>
                                    <span>Free gift wrapping available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cart data from PHP Blade
            const cartData = @json($cart);
            const codAvailable = @json($codAvailable);
            const paymentMethods = @json($paymentMethods);

            // Initialize checkout
            initCheckout(cartData, codAvailable, paymentMethods);
        });

        function initCheckout(cartData, codAvailable, paymentMethods) {
            // Setup payment methods based on COD availability
            setupPaymentMethods(codAvailable, paymentMethods);

            // Setup shipping methods
            setupShippingMethods(cartData);

            // Setup form validation
            setupFormValidation();

            // Setup shipping cost calculations
            setupShippingCostCalculations(cartData);

            // Setup pincode validation
            setupPincodeValidation();
        }

        function setupFormValidation() {
            const form = document.getElementById('checkoutForm');
            if (!form) return;

            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('border-red-500');
                        // Remove error message if exists
                        const errorMsg = this.parentNode.querySelector('.text-red-600.text-xs');
                        if (errorMsg) errorMsg.remove();
                    }
                });

                field.addEventListener('invalid', function(e) {
                    e.preventDefault();
                    this.classList.add('border-red-500');
                    
                    // Add error message if not exists
                    if (!this.parentNode.querySelector('.text-red-600')) {
                        const msg = document.createElement('span');
                        msg.className = 'text-red-600 text-xs mt-1 block';
                        msg.textContent = 'This field is required';
                        this.parentNode.appendChild(msg);
                    }
                });
            });
        }

        function setupPaymentMethods(codAvailable, paymentMethods) {
            const onlinePaymentRadio = document.querySelector('input[name="payment_method"][value="online"]');
            const codRadio = document.querySelector('input[name="payment_method"][value="cod"]');
            const codLabel = document.querySelector('label[for*="cod"]');

            if (!codAvailable && codRadio && onlinePaymentRadio && codLabel) {
                // Disable COD radio
                codRadio.disabled = true;
                codRadio.checked = false;

                // Check online payment by default
                onlinePaymentRadio.checked = true;

                // Show warning on COD label
                const warningSpan = document.createElement('span');
                warningSpan.className = 'ml-2 text-xs text-red-600 font-medium';
                warningSpan.textContent = '(Not available for some items)';
                warningSpan.id = 'cod-warning';

                codLabel.appendChild(warningSpan);

                // Add visual indication
                codLabel.classList.add('opacity-60', 'cursor-not-allowed');

                // Update order button text
                updateOrderButtonText();
            }

            // Listen to payment method changes
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateOrderButtonText();
                });
            });
        }

        function updateOrderButtonText() {
            const orderButtonText = document.getElementById('orderButtonText');
            const totalAmount = document.getElementById('totalAmount');
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            if (orderButtonText && totalAmount && selectedPayment) {
                const total = totalAmount.textContent.replace('₹', '');
                const paymentMethod = selectedPayment.value;

                if (paymentMethod === 'cod') {
                    orderButtonText.textContent = `Place COD Order ₹${total}`;
                } else {
                    orderButtonText.textContent = `Place Order & Pay ₹${total}`;
                }
            }
        }

        function setupShippingMethods(cartData) {
            // Check if free shipping is available
            const freeShippingAvailable = cartData.subtotal >=
                {{ config('services.shipping.free_shipping_min_amount', 999) }};

            // Update shipping method labels if free shipping is available
            if (freeShippingAvailable) {
                const standardShippingLabel = document.getElementById('standardShippingCost');
                if (standardShippingLabel) {
                    standardShippingLabel.textContent = 'FREE';
                    standardShippingLabel.classList.add('text-green-600');
                    standardShippingLabel.classList.remove('text-amber-700');
                }
            }
        }

        function setupShippingCostCalculations(cartData) {
            const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
            const orderButtonText = document.getElementById('orderButtonText');
            const shippingCostDisplay = document.getElementById('shippingCostDisplay');
            const totalAmount = document.getElementById('totalAmount');

            // Get initial values from PHP
            const subtotal = parseFloat(cartData.subtotal);
            const tax = parseFloat(cartData.tax_total);
            const currentShipping = parseFloat(cartData.shipping_total);
            const discount = parseFloat(cartData.discount_total || 0);

            shippingRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    let shippingFee = 0;

                    // Calculate new shipping fee based on selection using data-cost attribute
                    if (this.dataset.cost) {
                        shippingFee = parseFloat(this.dataset.cost);
                    } else {
                         // Fallback for hardcoded methods if any remain (legacy support)
                         if (this.value === 'standard') {
                            shippingFee = subtotal >=
                                {{ config('services.shipping.free_shipping_min_amount', 999) }} ? 0 :
                                {{ config('services.shipping.standard_cost', 50) }};
                        } else if (this.value === 'express') {
                            shippingFee = {{ config('services.shipping.express_cost', 199) }};
                        } else if (this.value === 'overnight') {
                            shippingFee = {{ config('services.shipping.overnight_cost', 499) }};
                        }
                    }

                    // Calculate new total
                    const newTotal = subtotal + tax + shippingFee - discount;

                    // Update shipping cost display
                    updateShippingDisplay(shippingFee);

                    // Update total amount
                    updateTotalAmount(newTotal);

                    // Update order button text
                    updateOrderButtonWithTotal(newTotal);

                    // Add hidden input for shipping cost
                    updateShippingCostInput(shippingFee);
                });
            });
        }

        function updateShippingDisplay(shippingFee) {
            const shippingCostDisplay = document.getElementById('shippingCostDisplay');
            if (shippingCostDisplay) {
                shippingCostDisplay.textContent = shippingFee === 0 ? 'FREE' : '₹' + shippingFee.toFixed(2);
                shippingCostDisplay.className = shippingFee === 0 ?
                    'font-semibold text-green-600' : 'font-semibold text-amber-700';
            }
        }

        function updateTotalAmount(total) {
            const totalAmount = document.getElementById('totalAmount');
            if (totalAmount) {
                totalAmount.textContent = '₹' + total.toLocaleString('en-IN', {
                    minimumFractionDigits: 2
                });
            }
        }

        function updateOrderButtonWithTotal(total) {
            const orderButtonText = document.getElementById('orderButtonText');
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            if (orderButtonText && selectedPayment) {
                if (selectedPayment.value === 'Cash on Delivery') {
                    orderButtonText.textContent = 'Place COD Order ₹' + total.toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    });
                } else {
                    orderButtonText.textContent = 'Place Order & Pay ₹' + total.toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    });
                }
            }
        }

        function updateShippingCostInput(shippingFee) {
            let shippingInput = document.getElementById('shipping_cost_input');
            if (!shippingInput) {
                shippingInput = document.createElement('input');
                shippingInput.type = 'hidden';
                shippingInput.name = 'shipping_cost';
                shippingInput.id = 'shipping_cost_input';
                document.getElementById('checkoutForm').appendChild(shippingInput);
            }
            shippingInput.value = shippingFee;
        }

        function setupPincodeValidation() {
            const pincodeInput = document.querySelector('input[name="pincode"]');
            const shippingMethodSection = document.querySelector('.shipping-method-section');

            if (pincodeInput) {
                let timeout;

                pincodeInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        validatePincode(this.value);
                    }, 1000);
                });
            }
        }

        async function validatePincode(pincode) {
            if (pincode.length < 6) return;

            try {
                const response = await fetch('{{ route('customer.checkout.shipping.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pincode: pincode
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showPincodeMessage('Shipping available to this location', 'success');
                    updateShippingOptions(data.available_couriers);
                } else {
                    showPincodeMessage(data.message || 'Shipping not available to this location', 'error');
                }
            } catch (error) {
                console.error('Pincode validation error:', error);
            }
        }

        function showPincodeMessage(message, type) {
            // Remove existing messages
            const existingMessage = document.getElementById('pincode-message');
            if (existingMessage) {
                existingMessage.remove();
            }

            // Create message element
            const pincodeInput = document.querySelector('input[name="pincode"]');
            const messageDiv = document.createElement('div');
            messageDiv.id = 'pincode-message';
            messageDiv.className = `mt-2 text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
            messageDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-1"></i>
        ${message}
    `;

            pincodeInput.parentNode.appendChild(messageDiv);
        }

        function updateShippingOptions(couriers) {
            const container = document.getElementById('shipping-method-container');
            if (!container) return;

            container.innerHTML = '';
            
            // Remove placeholder if exists (container.innerHTML = '' already does this, but good to be explicit in comment)
            
            // Sort couriers by rate cheap to expensive
            couriers.sort((a, b) => a.rate - b.rate);

            couriers.slice(0, 3).forEach((courier, index) => {
                const isFirst = index === 0;
                const cost = parseFloat(courier.rate);
                
                const html = `
                <label class="flex items-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:border-amber-500">
                    <input type="radio" name="shipping_method"
                        value="${courier.courier_id}" class="mr-3 text-amber-600"
                        ${isFirst ? 'checked' : ''}
                        data-cost="${cost}"
                        data-name="${courier.name}">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">${courier.name} (${courier.service_type})</p>
                        <p class="text-sm text-gray-600">Estimated delivery: ${courier.estimated_days} days</p>
                    </div>
                    <span class="font-bold text-amber-700">
                        ₹${cost.toFixed(2)}
                    </span>
                </label>`;
                
                container.insertAdjacentHTML('beforeend', html);
            });

            // Re-attach event listeners to new radio buttons
            const cartData = @json($cart);
            setupShippingCostCalculations(cartData);
            
            // Trigger change event on the first (checked) radio to update totals
            const firstRadio = container.querySelector('input[type="radio"]:checked');
            if (firstRadio) {
                firstRadio.dispatchEvent(new Event('change'));
            }
        }


        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.custom-notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `custom-notification fixed top-4 right-4 px-6 py-3 rounded-full shadow-lg z-50 ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300'}`;
            notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
        ${message}
    `;

            // Add animation
            notification.style.animation = 'slideInRight 0.3s ease-out';

            document.body.appendChild(notification);

            // Remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'fadeOut 0.5s ease-out forwards';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }, 5000);
        }

        // Add CSS for animations
        (function() {
            const style = document.createElement('style');
            style.textContent = `
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

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    .custom-notification {
        animation: slideInRight 0.3s ease-out;
        backdrop-filter: blur(10px);
    }

    .cursor-not-allowed {
        cursor: not-allowed !important;
    }
`;
            document.head.appendChild(style);
        })();
    </script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkoutForm');

            form.addEventListener('submit', async function(e) {
                const paymentMethod = document.querySelector(
                    'input[name="payment_method"]:checked'
                ).value;

                // ✅ COD → normal submit
                if (paymentMethod === 'cod') {
                    return true;
                }

                // ❌ Online → stop normal submit
                e.preventDefault();

                const formData = new FormData(form);

                const response = await fetch(
                    "{{ route('customer.checkout.razorpay.order') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: formData
                    }
                );

                const data = await response.json();

                if (!data.success) {
                    showNotification(data.message || 'Payment failed', 'error');
                    return;
                }

                const options = {
                    key: data.key_id,
                    amount: data.amount,
                    currency: "INR",
                    name: "APIQO Jewellery",
                    description: "Secure Checkout",
                    order_id: data.order_id,
                    handler: function(response) {
                        submitPayment(response);
                    },
                    theme: {
                        color: "#D97706"
                    }
                };

                new Razorpay(options).open();
            });
        });

        function submitPayment(response) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('customer.checkout.payment.callback') }}";

            form.innerHTML = `
        @csrf
        <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
        <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
        <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
    `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
