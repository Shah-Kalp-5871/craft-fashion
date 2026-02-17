@extends('customer.layouts.master')

@section('title', 'Checkout | Craft Fashion')
@section('description', 'Complete your purchase with secure payment and fast delivery')

@push('styles')
<style>
    /* Custom styles for checkout */
    .order-summary-container {
        scrollbar-width: thin;
        scrollbar-color: #c98f83 transparent;
    }

    .order-summary-container::-webkit-scrollbar {
        width: 6px;
    }

    .order-summary-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .order-summary-container::-webkit-scrollbar-thumb {
        background-color: #c98f83;
        border-radius: 20px;
    }

    .checkout-input:focus {
        box-shadow: 0 0 0 3px rgba(201, 143, 131, 0.1);
    }

    .delivery-option {
        transition: all 0.3s ease;
    }

    .delivery-option:hover {
        transform: translateY(-2px);
    }

    .delivery-option.selected {
        border-color: #c98f83;
        background-color: rgba(201, 143, 131, 0.05);
    }

    .payment-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .payment-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .payment-btn:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .payment-btn:focus:not(:active)::after {
        animation: ripple 1s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        20% {
            transform: scale(25, 25);
            opacity: 0.3;
        }
        100% {
            opacity: 0;
            transform: scale(40, 40);
        }
    }

    /* Loading spinner */
    .spinner {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 2px solid white;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<!-- Modern Hero Section -->
<section class="relative py-8 md:py-16 bg-gradient-to-br from-primary/10 via-white to-primary/5 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-xs md:text-sm font-medium mb-4 md:mb-6">
                <i class="fas fa-lock mr-2"></i>
                Secure Checkout
            </div>

            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold font-playfair text-dark mb-4 md:mb-6 leading-tight px-4">
                Complete Your <span class="text-primary">Order</span>
            </h1>

            <p class="text-base md:text-xl text-secondary max-w-2xl mx-auto leading-relaxed">
                Finalize your purchase with secure payment and fast delivery
            </p>
        </div>
    </div>
</section>

<!-- Checkout Content -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Progress Steps -->
        <div class="max-w-4xl mx-auto mb-8 md:mb-12 overflow-x-auto" data-aos="fade-down">
            <div class="flex items-center justify-between text-xs md:text-base">
                <div class="flex items-center text-primary">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-2 md:mr-3">
                        1
                    </div>
                    <span class="font-semibold">Cart</span>
                </div>
                <div class="flex-1 h-1 bg-primary mx-2 md:mx-4"></div>
                <div class="flex items-center text-primary">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-2 md:mr-3">
                        2
                    </div>
                    <span class="font-semibold text-xs md:text-base">Checkout</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 md:mx-4"></div>
                <div class="flex items-center text-secondary">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-200 text-secondary rounded-full flex items-center justify-center font-bold mr-2 md:mr-3">
                        3
                    </div>
                    <span class="font-semibold text-xs md:text-base">Confirmation</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <!-- Customer Details Form -->
            <div data-aos="fade-right" class="order-1 lg:order-1">
                <div class="bg-white rounded-3xl shadow-lg border border-primary/10 p-4 md:p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-user text-primary text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold font-playfair text-dark">Customer Information</h2>
                            <p class="text-secondary text-sm md:text-base">Enter your details to complete the order</p>
                        </div>
                    </div>

                    <form id="checkout-form" action="{{ route('customer.checkout.process') }}" method="post" class="space-y-6">
                        @csrf
                        
                        <!-- Step 1: Pincode Verification (Always Visible) -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    1
                                </div>
                                <h3 class="text-lg font-bold text-dark">Verify Delivery Pincode</h3>
                            </div>
                            <div class="max-w-md">
                                <label for="pincode" class="block text-sm font-semibold text-dark mb-2">Enter Your Pincode *</label>
                                <div class="relative">
                                    <input type="text" id="pincode" name="pincode" required maxlength="6" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 0"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-white checkout-input text-lg font-semibold"
                                        placeholder="123456" autofocus>
                                    <div id="pincode-loader" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                                        <i class="fas fa-spinner fa-spin text-primary"></i>
                                    </div>
                                </div>
                                <div id="pincode-feedback" class="text-xs mt-2 font-medium min-h-[1.25rem]"></div>
                                <span id="pincode-error" class="text-red-500 text-sm hidden mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Pincode is required
                                </span>
                            </div>
                        </div>

                        <!-- Step 2: Customer Information (Hidden Initially) -->
                        <div id="customer-info-section" class="hidden">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h3 class="text-lg font-bold text-dark">Customer Information</h3>
                            </div>

                            <!-- Personal Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-dark mb-2">Full Name *</label>
                                    <input type="text" id="name" name="name" required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white checkout-input"
                                        placeholder="Enter your full name">
                                    <span id="name-error" class="text-red-500 text-sm hidden mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Name is required
                                    </span>
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-dark mb-2">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" required maxlength="10" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 0"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white checkout-input"
                                        placeholder="1234567890">
                                    <span id="phone-error" class="text-red-500 text-sm hidden mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Valid 10-digit phone number is required
                                    </span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="email" class="block text-sm font-semibold text-dark mb-2">Email Address</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white checkout-input"
                                    placeholder="your.email@example.com">
                            </div>

                            <!-- Address Information -->
                            <div class="mb-6">
                                <label for="address" class="block text-sm font-semibold text-dark mb-2">Delivery Address *</label>
                                <textarea id="address" name="address" rows="3" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white checkout-input resize-none"
                                    placeholder="Enter your complete delivery address"></textarea>
                                <span id="address-error" class="text-red-500 text-sm hidden mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Address is required
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="city" class="block text-sm font-semibold text-dark mb-2">City *</label>
                                    <input type="text" id="city" name="city" required readonly
                                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-100 checkout-input"
                                        placeholder="Auto-filled from pincode">
                                    <span id="city-error" class="text-red-500 text-sm hidden mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> City is required
                                    </span>
                                </div>

                                <div>
                                    <label for="state" class="block text-sm font-semibold text-dark mb-2">State *</label>
                                    <input type="text" id="state" name="state" required readonly
                                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-100 checkout-input"
                                        placeholder="Auto-filled from pincode">
                                    <span id="state-error" class="text-red-500 text-sm hidden mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> State is required
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Options (Hidden but functionality preserved for backend) -->
                        <input type="hidden" name="delivery" value="standard">

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-dark mb-4">Payment Method</h3>
                            <div class="space-y-3">
                                @if($codAvailable)
                                <label class="flex items-center p-4 border-2 {{ !$paymentMethods['online']['available'] ? 'border-primary bg-primary/5' : 'border-gray-200' }} rounded-2xl cursor-pointer hover:border-primary/50 payment-option {{ !$paymentMethods['online']['available'] ? 'selected' : '' }}" data-value="cod">
                                    <input type="radio" name="payment_method" value="cod" class="text-primary focus:ring-primary" {{ !$paymentMethods['online']['available'] ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="font-medium text-dark">Cash on Delivery (COD)</span>
                                        <p class="text-sm text-secondary">Pay when you receive your order</p>
                                    </div>
                                </label>
                                @endif

                                @if($paymentMethods['online']['available'])
                                <label class="flex items-center p-4 border-2 border-primary rounded-2xl cursor-pointer bg-primary/5 payment-option selected" data-value="online">
                                    <input type="radio" name="payment_method" value="online" class="text-primary focus:ring-primary" checked>
                                    <div class="ml-3">
                                        <span class="font-medium text-dark">Online Payment</span>
                                        <p class="text-sm text-secondary">Secure payment via UPI, Cards, NetBanking</p>
                                    </div>
                                </label>
                                @endif

                                @if(!$codAvailable && !$paymentMethods['online']['available'])
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        No payment methods are currently available for your order.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div data-aos="fade-left" class="sticky top-24 h-fit order-2 lg:order-2 mt-8 lg:mt-0">
                <div class="bg-white rounded-3xl shadow-lg border border-primary/10 p-4 md:p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-bag text-primary text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold font-playfair text-dark">Order Summary</h2>
                            <p class="text-secondary">Review your items and total</p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="space-y-4 mb-6 max-h-60 md:max-h-80 overflow-y-auto pr-2 order-summary-container">
                        @foreach($cart['items'] as $item)
                        <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-primary/30 transition duration-300">
                            <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" 
                                 alt="{{ $item['product_name'] }}" class="w-16 h-16 object-cover rounded-2xl shadow-sm">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-dark text-sm mb-1 truncate">{{ $item['product_name'] }}</h4>
                                <p class="text-xs text-secondary mb-1">Qty: {{ $item['quantity'] }}</p>
                                <p class="text-sm font-semibold text-primary">₹{{ number_format($item['total'], 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-secondary">Subtotal</span>
                            <span class="font-semibold text-dark">₹{{ number_format($cart['subtotal'], 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center py-2">
                            <span class="text-secondary">Shipping</span>
                            <span class="font-semibold text-dark shipping-cost">
                                @if(($cart['shipping_total'] ?? 0) == 0)
                                    <span class="text-green-500">FREE</span>
                                @else
                                    ₹{{ number_format($cart['shipping_total'], 2) }}
                                @endif
                            </span>
                        </div>
                        
                        @if(($cart['discount_total'] ?? 0) > 0)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-secondary">Discount</span>
                            <span class="font-semibold text-green-600">-₹{{ number_format($cart['discount_total'], 2) }}</span>
                        </div>
                        @endif

                        <div class="border-t border-gray-200 pt-4 flex justify-between items-center text-lg font-bold">
                            <span class="text-dark">Total Amount</span>
                            <span class="text-primary text-xl total-amount">₹{{ number_format($cart['grand_total'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button id="pay-now-btn" type="submit" form="checkout-form" class="w-full bg-primary text-white py-4 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 flex items-center justify-center group payment-btn">
                        <i class="fas fa-lock mr-3 group-hover:scale-110 transition-transform"></i>
                        <span>Place Order - ₹{{ number_format($cart['grand_total'], 2) }}</span>
                    </button>

                    <!-- Security Badges -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center mb-4">
                            <p class="text-sm text-secondary mb-3">100% Secure Payment</p>
                            <div class="flex justify-center space-x-4">
                                <div class="w-10 h-6 bg-gray-100 rounded flex items-center justify-center">
                                    <i class="fab fa-cc-visa text-blue-600 text-lg"></i>
                                </div>
                                <div class="w-10 h-6 bg-gray-100 rounded flex items-center justify-center">
                                    <i class="fab fa-cc-mastercard text-red-600 text-lg"></i>
                                </div>
                                <div class="w-10 h-6 bg-gray-100 rounded flex items-center justify-center">
                                    <i class="fas fa-credit-card text-blue-500 text-lg"></i>
                                </div>
                                <div class="w-10 h-6 bg-gray-100 rounded flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-green-500 text-lg"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-center text-secondary">
                            Your payment information is secure and encrypted
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Features -->
<section class="py-16 bg-gradient-to-br from-gray-50 to-primary/5">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold font-playfair text-dark mb-12">Why Shop With Confidence</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-aos="fade-up" class="text-center p-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-dark mb-2">Secure Payment</h3>
                    <p class="text-secondary">Your payment information is protected with bank-level security</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="text-center p-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-dark mb-2">Free Home Try</h3>
                    <p class="text-secondary">Try before you buy with our free home trial service</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" class="text-center p-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-undo text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-dark mb-2">Easy Returns</h3>
                    <p class="text-secondary">7-day return policy with free size exchanges</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cart Data for dynamic updates
        const subtotal = {{ $cart['subtotal'] }};
        const discount = {{ $cart['discount_total'] ?? 0 }};
        const tax = {{ $cart['tax_total'] ?? 0 }};
        
        const updateSummary = (shippingCost) => {
            const shippingEl = document.querySelector('.shipping-cost');
            const totalEl = document.querySelector('.total-amount');
            const payBtnText = document.querySelector('#pay-now-btn span');
            
            const total = subtotal + tax + shippingCost - discount;
            
            if (shippingCost === 0) {
                shippingEl.innerHTML = '<span class="text-green-500">FREE</span>';
            } else {
                shippingEl.textContent = '₹' + shippingCost.toFixed(2);
            }
            
            totalEl.textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
            if (payBtnText) {
                payBtnText.textContent = 'Place Order - ₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
            }
        };

        // Initialize with Standard Shipping (100)
        updateSummary(100);



        // Selection of Payment Method
        const paymentOptions = document.querySelectorAll('.payment-option');
        paymentOptions.forEach(option => {
            option.addEventListener('click', function () {
                paymentOptions.forEach(opt => {
                    opt.classList.remove('selected', 'bg-primary/5', 'border-primary');
                    opt.classList.add('border-gray-200');
                });
                this.classList.add('selected', 'bg-primary/5', 'border-primary');
                this.classList.remove('border-gray-200');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Pincode Auto-Verification Logic
        const pincodeInput = document.getElementById('pincode');
        const pincodeLoader = document.getElementById('pincode-loader');
        const feedbackEl = document.getElementById('pincode-feedback');
        const payBtn = document.getElementById('pay-now-btn');
        let isPincodeVerified = false;
        let pincodeTimeout = null;

        console.log('Pincode verification initialized');
        console.log('Elements found:', {
            pincodeInput: !!pincodeInput,
            pincodeLoader: !!pincodeLoader,
            feedbackEl: !!feedbackEl,
            payBtn: !!payBtn
        });

        // Disable Place Order button initially
        if (payBtn) {
            payBtn.disabled = true;
            payBtn.classList.add('opacity-50', 'cursor-not-allowed');
            payBtn.title = "Please enter a valid 6-digit pincode";
            console.log('Place Order button disabled initially');
        }
        
        if (pincodeInput) {
            console.log('Adding input event listener to pincode field');
            
            // Auto-verify pincode when 6 digits are entered
            pincodeInput.addEventListener('input', function() {
                const pincode = this.value.trim();
                console.log('Pincode input changed:', pincode, 'Length:', pincode.length);
                
                // Reset verification state on change
                isPincodeVerified = false;
                if (payBtn) {
                    payBtn.disabled = true;
                    payBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    payBtn.title = "Please enter a valid 6-digit pincode";
                }
                feedbackEl.innerHTML = '';
                
                // Hide customer information section when pincode changes
                const customerInfoSection = document.getElementById('customer-info-section');
                if (customerInfoSection && !customerInfoSection.classList.contains('hidden')) {
                    customerInfoSection.classList.add('hidden');
                    console.log('Customer information section hidden (pincode changed)');
                }
                
                // Clear existing timeout
                if (pincodeTimeout) {
                    clearTimeout(pincodeTimeout);
                    console.log('Cleared previous timeout');
                }
                
                // Auto-trigger verification when 6 digits are entered
                if (pincode.length === 6) {
                    console.log('6 digits entered, scheduling verification in 500ms...');
                    pincodeTimeout = setTimeout(() => checkPincode(pincode), 500);
                } else {
                    console.log('Waiting for 6 digits...');
                }
            });
        } else {
            console.error('Pincode input field not found!');
        }
        
        async function checkPincode(pincode) {
            console.log('=== Starting pincode verification ===');
            console.log('Pincode to check:', pincode);
            console.log('API URL:', "{{ route('customer.checkout.shipping.check') }}");
            
            // Show loader
            if (pincodeLoader) {
                pincodeLoader.classList.remove('hidden');
                console.log('Loader shown');
            }
            feedbackEl.innerHTML = '<span class="text-gray-500"><i class="fas fa-circle-notch fa-spin mr-1"></i> Checking availability...</span>';
            
            try {
                console.log('Sending axios POST request...');
                const response = await axios.post("{{ route('customer.checkout.shipping.check') }}", {
                    pincode: pincode
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                console.log('✅ API Response received:', response.data);
                
                if (response.data.success) {
                    // Get estimated delivery days
                    let days = '3-5';
                    if (response.data.estimated_delivery) {
                        days = response.data.estimated_delivery;
                    } else if (response.data.available_couriers && response.data.available_couriers.length > 0) {
                        days = response.data.available_couriers[0].estimated_days || '3-5';
                    }
                    
                    console.log('✅ Pincode verified! Delivery days:', days);
                    feedbackEl.innerHTML = `<span class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i> Pincode verified successfully! (Delivery in ${days} days)</span>`;
                    
                    // Auto-fill City and State from response
                    const cityInput = document.getElementById('city');
                    const stateInput = document.getElementById('state');
                    
                    if (response.data.city && cityInput) {
                        cityInput.value = response.data.city;
                        console.log('✅ City auto-filled:', response.data.city);
                    }
                    
                    if (response.data.state && stateInput) {
                        stateInput.value = response.data.state;
                        console.log('✅ State auto-filled:', response.data.state);
                    }
                    
                    // Show Customer Information Section
                    const customerInfoSection = document.getElementById('customer-info-section');
                    if (customerInfoSection) {
                        customerInfoSection.classList.remove('hidden');
                        customerInfoSection.classList.add('animate-fadeIn');
                        console.log('✅ Customer information section shown');
                        
                        // Scroll to customer info section smoothly
                        setTimeout(() => {
                            customerInfoSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 100);
                    }
                    
                    // Enable Place Order Button
                    isPincodeVerified = true;
                    if (payBtn) {
                        payBtn.disabled = false;
                        payBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        payBtn.title = "";
                        console.log('✅ Place Order button enabled');
                    }
                } else {
                    // Show error message
                    console.log('❌ Pincode verification failed:', response.data.message);
                    
                    let errorMsg = response.data.message || 'Pincode is not verified. Enter correct pincode';
                    if (response.data.debug_error) {
                        errorMsg += ` <span class="text-xs text-red-400">(${response.data.debug_error})</span>`;
                    }
                    
                    feedbackEl.innerHTML = `<span class="text-red-500 font-semibold"><i class="fas fa-times-circle mr-1"></i> ${errorMsg}</span>`;
                    isPincodeVerified = false;
                    
                    // Hide Customer Information Section
                    const customerInfoSection = document.getElementById('customer-info-section');
                    if (customerInfoSection) {
                        customerInfoSection.classList.add('hidden');
                        console.log('❌ Customer information section hidden');
                    }
                }
            } catch (error) {
                console.error('❌ Pincode verification error:', error);
                console.error('Error details:', {
                    message: error.message,
                    response: error.response?.data,
                    status: error.response?.status
                });
                
                // Check if it's a configuration error (development mode)
                if (error.response?.data?.message && 
                    (error.response.data.message.includes('configuration') || 
                     error.response.data.message.includes('credentials'))) {
                    // Allow checkout in development mode
                    console.log('⚠️ Development mode - allowing checkout');
                    feedbackEl.innerHTML = '<span class="text-yellow-600"><i class="fas fa-exclamation-triangle mr-1"></i> Shipping verification unavailable (Development Mode)</span>';
                    isPincodeVerified = true;
                    if (payBtn) {
                        payBtn.disabled = false;
                        payBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        payBtn.title = "";
                    }
                } else {
                    // Network or other error
                    feedbackEl.innerHTML = '<span class="text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> Unable to verify pincode. Please try again.</span>';
                    isPincodeVerified = false;
                }
            } finally {
                // Hide loader
                if (pincodeLoader) {
                    pincodeLoader.classList.add('hidden');
                    console.log('Loader hidden');
                }
                console.log('=== Pincode verification complete ===');
            }
        }

        const checkoutForm = document.getElementById('checkout-form');
        const payBtnText = payBtn ? payBtn.querySelector('span') : null;

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', async function (e) {
                if (!isPincodeVerified) {
                    e.preventDefault();
                    Swal.fire('Validation Error', 'Please verify your pincode before placing the order.', 'warning');
                    pincodeInput.focus();
                    return;
                }
                
                const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
                if(!paymentMethodInput) return;
                
                const paymentMethod = paymentMethodInput.value;
                
                if (paymentMethod === 'online') {
                e.preventDefault();
                
                // Show loading state
                payBtn.disabled = true;
                const originalText = payBtnText.textContent;
                payBtnText.textContent = 'Processing...';
                
                try {
                    const formData = new FormData(checkoutForm);
                    const response = await axios.post("{{ route('customer.checkout.razorpay.order') }}", formData);
                    
                    if (response.data.success) {
                        const options = {
                            key: response.data.key_id,
                            amount: response.data.amount,
                            currency: response.data.currency,
                            name: "{{ config('constants.SITE_NAME', 'Craft Fashion') }}",
                            description: "Payment for Order",
                            order_id: response.data.order_id,
                            handler: function (response) {
                                // Payment success - redirect to callback
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = "{{ route('customer.checkout.payment.callback') }}";
                                
                                const csrfToken = document.createElement('input');
                                csrfToken.type = 'hidden';
                                csrfToken.name = '_token';
                                csrfToken.value = "{{ csrf_token() }}";
                                form.appendChild(csrfToken);
                                
                                const paymentId = document.createElement('input');
                                paymentId.type = 'hidden';
                                paymentId.name = 'razorpay_payment_id';
                                paymentId.value = response.razorpay_payment_id;
                                form.appendChild(paymentId);
                                
                                const orderId = document.createElement('input');
                                orderId.type = 'hidden';
                                orderId.name = 'razorpay_order_id';
                                orderId.value = response.razorpay_order_id;
                                form.appendChild(orderId);
                                
                                const signature = document.createElement('input');
                                signature.type = 'hidden';
                                signature.name = 'razorpay_signature';
                                signature.value = response.razorpay_signature;
                                form.appendChild(signature);
                                
                                document.body.appendChild(form);
                                form.submit();
                            },
                            prefill: {
                                name: document.getElementById('name').value,
                                email: document.getElementById('email').value,
                                contact: document.getElementById('phone').value
                            },
                            theme: {
                                color: "#000000"
                            },
                            modal: {
                                ondismiss: function() {
                                    payBtn.disabled = false;
                                    payBtnText.textContent = originalText;
                                }
                            }
                        };
                        const rzp = new Razorpay(options);
                        rzp.open();
                    } else {
                        Swal.fire('Error', response.data.message || 'Something went wrong', 'error');
                        payBtn.disabled = false;
                        payBtnText.textContent = originalText;
                    }
                } catch (error) {
                    console.error(error);
                    const errorMessage = error.response?.data?.message || 'Please check your information and try again.';
                    
                    if (error.response?.status === 422) {
                        const errors = error.response.data.errors;
                        let errorHtml = '<ul class="text-left list-disc pl-4">';
                        Object.values(errors).forEach(err => {
                            errorHtml += `<li>${err[0]}</li>`;
                        });
                        errorHtml += '</ul>';
                        
                        Swal.fire({
                            title: 'Validation Error',
                            html: errorHtml,
                            icon: 'error'
                        });
                    } else {
                        Swal.fire('Error', errorMessage, 'error');
                    }
                    
                    payBtn.disabled = false;
                    payBtnText.textContent = originalText;
                }
                } else {
                    // COD - just show processing state
                    payBtn.disabled = true;
                    payBtnText.textContent = 'Placing Order...';
                }
            });
        }

        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if(phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
        }
    });
</script>
@endpush
@endsection