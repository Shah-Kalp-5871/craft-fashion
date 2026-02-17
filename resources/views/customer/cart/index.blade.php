@extends('customer.layouts.master')

@section('title', 'Your Shopping Cart | ' . config('constants.SITE_NAME'))

@section('content')
<!-- Modern Hero Section -->
<section class="relative py-8 sm:py-12 md:py-16 bg-gradient-to-br from-primary/10 via-white to-primary/5 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-48 h-48 sm:w-72 sm:h-72 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-64 h-64 sm:w-96 sm:h-96 bg-primary/5 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-primary/10 text-dark px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium mb-4 sm:mb-6">
                <i class="fas fa-shopping-bag mr-2 text-sm sm:text-base"></i>
                Your Shopping Journey
            </div>
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-playfair text-dark mb-4 sm:mb-6 leading-tight px-4">
                Your <span class="text-primary">Shopping Cart</span>
            </h1>
            
            <p class="text-base sm:text-lg md:text-xl text-secondary max-w-2xl mx-auto leading-relaxed px-4">
                Review your selected items and proceed to checkout
            </p>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-8 sm:py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl mb-6 sm:mb-8 flex items-start sm:items-center text-sm sm:text-base" data-aos="fade-down">
                <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0"></i>
                <span>{{ session('message') }}</span>
            </div>
        @endif
        
        @if(empty($cart['items']))
            <!-- Empty Cart State -->
            <div class="text-center py-12 sm:py-16 md:py-20 px-4" data-aos="fade-up">
                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-cart-shopping text-4xl sm:text-5xl text-primary"></i>
                </div>
                <h3 class="text-2xl sm:text-3xl font-bold font-playfair text-dark mb-3 sm:mb-4">Your Cart is Empty</h3>
                <p class="text-base sm:text-lg md:text-xl text-secondary mb-6 sm:mb-8 max-w-md mx-auto">
                    Looks like you haven't added anything to your cart yet. Let's find something beautiful for you!
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                    <a href="{{ route('customer.products.list') }}" class="bg-primary text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 inline-flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-bag-shopping mr-2 sm:mr-3"></i>
                        <span>Start Shopping</span>
                    </a>
                    <a href="{{ config('constants.WHATSAPP_LINK') }}" class="bg-white text-dark border border-primary px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary hover:text-white inline-flex items-center justify-center text-sm sm:text-base">
                        <i class="fab fa-whatsapp mr-2 sm:mr-3 text-green-500"></i>
                        <span>Get Style Advice</span>
                    </a>
                </div>
            </div>
        @else
            @php
                $subtotal = $cart['subtotal'];
                $shipping = $cart['shipping_total'];
                $total = $cart['grand_total'];
                $itemCount = $cart['items_count'];
            @endphp
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg border border-primary/10 overflow-hidden" data-aos="fade-right">
                        <!-- Cart Header -->
                        <div class="bg-gradient-to-r from-primary/5 to-primary/10 px-4 sm:px-6 py-3 sm:py-4 border-b border-primary/20">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg sm:text-xl md:text-2xl font-bold font-playfair text-dark">Your Items</h2>
                                <span id="cart-header-count" class="bg-primary text-white px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                                    {{ $itemCount }} Item{{ $itemCount > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-3 sm:p-4 md:p-6">
                            <!-- Cart Items List -->
                            <div class="space-y-4 sm:space-y-6">
                                @foreach($cart['items'] as $item)
                                    <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-100 hover:border-primary/30 transition-all duration-300 group" data-item-row="{{ $item['id'] }}">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 relative w-full sm:w-auto">
                                            <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['product_name'] ?? 'Product' }}" 
                                                 class="w-full sm:w-20 md:w-24 h-32 sm:h-20 md:h-24 object-cover rounded-xl sm:rounded-2xl shadow-md group-hover:scale-105 transition duration-300">
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0 w-full">
                                            <h3 class="font-bold text-dark text-base sm:text-lg mb-1 line-clamp-2 sm:truncate">{{ $item['product_name'] ?? 'Product' }}</h3>
                                            
                                            <!-- Attributes -->
                                            @if(!empty($item['attributes']))
                                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-secondary mb-2 sm:mb-3">
                                                    @foreach($item['attributes'] as $name => $value)
                                                        <span class="bg-gray-100 px-2 py-0.5 sm:py-1 rounded">{{ $name }}: {{ $value }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <!-- Price & Quantity Section -->
                                            <div class="space-y-3">
                                                <!-- Price Row -->
                                                <div class="flex items-center justify-between">
                                                    <div class="text-left">
                                                        <span class="text-lg sm:text-xl font-bold text-primary">₹{{ number_format($item['unit_price'], 2) }}</span>
                                                    </div>
                                                    <!-- Item Total -->
                                                    <div class="lg:hidden">
                                                        <div id="item-total-mobile-{{ $item['id'] }}" class="text-base sm:text-lg font-bold text-dark">₹{{ number_format($item['total'], 2) }}</div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Quantity & Remove Row -->
                                                <div class="flex items-center justify-between gap-3">
                                                    <!-- Quantity Controls -->
                                                    <div class="flex items-center gap-2">
                                                        <label class="text-xs sm:text-sm font-medium text-dark whitespace-nowrap">Qty:</label>
                                                        <div class="flex items-center border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden">
                                                            <button type="button" data-type="minus" class="quantity-control w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 active:bg-gray-300 transition touch-manipulation" 
                                                                onclick="changeQuantity('{{ $item['id'] }}', -1)" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                                <i class="fas fa-minus text-xs sm:text-sm"></i>
                                                            </button>
                                                            <input type="number" id="qty-input-mobile-{{ $item['id'] }}" value="{{ $item['quantity'] }}" min="1"
                                                                   class="w-12 sm:w-14 px-0 py-2 border-0 text-center text-sm sm:text-base focus:ring-0 focus:outline-none bg-transparent quantity-input appearance-none [-moz-appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                                   onchange="manualUpdateQuantity(this, '{{ $item['id'] }}')"
                                                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                            <button type="button" data-type="plus" class="quantity-control w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 active:bg-gray-300 transition touch-manipulation"
                                                                onclick="changeQuantity('{{ $item['id'] }}', 1)">
                                                                <i class="fas fa-plus text-xs sm:text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Remove Button -->
                                                    <button type="button" onclick="removeItem('{{ $item['id'] }}')" 
                                                       class="text-red-500 hover:text-red-700 active:text-red-800 transition duration-300 flex items-center text-xs sm:text-sm font-medium whitespace-nowrap touch-manipulation">
                                                        <i class="fas fa-trash mr-1"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Desktop Layout (Hidden on Mobile) -->
                                            <div class="hidden lg:block mt-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-4">
                                                        <!-- Desktop Quantity (additional for desktop) -->
                                                        <!-- <div class="flex items-center gap-2">
                                                            <div class="flex items-center border border-gray-200 rounded-2xl overflow-hidden">
                                                                <button type="button" data-type="minus" class="quantity-control w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition" 
                                                                    onclick="changeQuantity('{{ $item['id'] }}', -1)" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                                    <i class="fas fa-minus text-sm"></i>
                                                                </button>
                                                                <input type="number" id="qty-input-{{ $item['id'] }}" value="{{ $item['quantity'] }}" min="1"
                                                                       class="w-12 px-0 py-2 border-0 text-center focus:ring-0 focus:outline-none bg-transparent quantity-input appearance-none [-moz-appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                                       onchange="manualUpdateQuantity(this, '{{ $item['id'] }}')"
                                                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                                <button type="button" data-type="plus" class="quantity-control w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition"
                                                                    onclick="changeQuantity('{{ $item['id'] }}', 1)">
                                                                    <i class="fas fa-plus text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                    
                                                    <!-- Desktop Item Total -->
                                                    <div class="text-right">
                                                        <div id="item-total-{{ $item['id'] }}" class="text-lg font-bold text-dark mb-2">₹{{ number_format($item['total'], 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Cart Actions -->
                            <div class="flex justify-start items-center mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                                <a href="{{ route('customer.products.list') }}" class="text-primary hover:text-primary/80 active:text-primary/70 transition duration-300 flex items-center font-semibold group text-sm sm:text-base touch-manipulation">
                                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-sm sm:text-base"></i>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1 lg:sticky lg:top-24 h-fit">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg border border-primary/10 p-4 sm:p-6" data-aos="fade-left">
                        <h2 class="text-xl sm:text-2xl font-bold font-playfair text-dark mb-4 sm:mb-6">Order Summary</h2>
                        
                        <!-- Pricing Breakdown -->
                        <div class="space-y-3 sm:space-y-4 mb-4 sm:mb-6">
                            <div class="flex justify-between items-center py-1.5 sm:py-2">
                                <span class="text-secondary text-sm sm:text-base">Subtotal</span>
                                <span id="summary-subtotal" class="font-semibold text-dark text-sm sm:text-base">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            @if($cart['discount_total'] > 0)
                            <div class="flex justify-between items-center py-1.5 sm:py-2 text-green-600">
                                <span class="text-sm sm:text-base">Discount</span>
                                <span id="summary-discount" class="font-semibold text-sm sm:text-base">-₹{{ number_format($cart['discount_total'], 2) }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-center py-1.5 sm:py-2 font-medium">
                                <span class="text-secondary text-sm sm:text-base">Tax Total</span>
                                <span id="summary-tax" class="text-dark text-sm sm:text-base">₹{{ number_format($cart['tax_total'], 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center py-1.5 sm:py-2">
                                <span class="text-secondary text-sm sm:text-base">Shipping</span>
                                <span id="summary-shipping" class="font-semibold text-sm sm:text-base text-dark">
                                    Calculated at checkout
                                </span>
                            </div>
                            
                            @if($shipping > 0)
                            <div class="bg-primary/5 border border-primary/20 rounded-xl sm:rounded-2xl p-2.5 sm:p-3 text-center">
                                <p class="text-primary text-xs sm:text-sm font-medium">Free shipping on orders above ₹1000!</p>
                            </div>
                            @endif
                            
                            <div class="border-t border-gray-200 pt-3 sm:pt-4 flex justify-between items-center text-base sm:text-lg font-bold">
                                <span class="text-dark">Total Amount</span>
                                <span id="summary-total" class="text-primary text-lg sm:text-xl">₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Checkout Actions -->
                        <div class="space-y-3 sm:space-y-4">
                            <a href="{{ route('customer.checkout.index') }}" 
                               class="block w-full bg-primary text-white py-3 sm:py-4 rounded-xl sm:rounded-2xl font-semibold text-center text-sm sm:text-base transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 active:scale-100 flex items-center justify-center group touch-manipulation">
                                <span>Proceed to Checkout</span>
                                <i class="fas fa-lock ml-2 sm:ml-3 group-hover:scale-110 transition-transform text-sm sm:text-base"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Features Banner -->
<section class="py-8 sm:py-12 md:py-16 bg-gradient-to-r from-primary to-primary/90 text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
            <div data-aos="fade-up" class="p-3 sm:p-4 md:p-6 text-center">
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3 md:mb-4">
                    <i class="fas fa-truck text-lg sm:text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-sm sm:text-base md:text-xl font-bold mb-1 sm:mb-2">Fast Delivery</h3>
                <p class="text-white/80 text-xs sm:text-sm md:text-base">Express shipping across India</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="100" class="p-3 sm:p-4 md:p-6 text-center">
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3 md:mb-4">
                    <i class="fas fa-undo text-lg sm:text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-sm sm:text-base md:text-xl font-bold mb-1 sm:mb-2">Easy Returns</h3>
                <p class="text-white/80 text-xs sm:text-sm md:text-base">7-day return policy</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="200" class="p-3 sm:p-4 md:p-6 text-center">
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3 md:mb-4">
                    <i class="fas fa-shield-alt text-lg sm:text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-sm sm:text-base md:text-xl font-bold mb-1 sm:mb-2">Secure Payment</h3>
                <p class="text-white/80 text-xs sm:text-sm md:text-base">100% secure transactions</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="300" class="p-3 sm:p-4 md:p-6 text-center">
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3 md:mb-4">
                    <i class="fas fa-award text-lg sm:text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-sm sm:text-base md:text-xl font-bold mb-1 sm:mb-2">Premium Quality</h3>
                <p class="text-white/80 text-xs sm:text-sm md:text-base">Exquisite boutique designs</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
const formatCurrency = (amount) => {
    return '₹' + parseFloat(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

// Debounce helper
const debounceTimers = {};
function debounce(key, func, delay) {
    if (debounceTimers[key]) clearTimeout(debounceTimers[key]);
    debounceTimers[key] = setTimeout(func, delay);
}

function getQtyInputs(itemId) {
    const inputs = [];
    const desktopInput = document.getElementById(`qty-input-${itemId}`);
    const mobileInput = document.getElementById(`qty-input-mobile-${itemId}`);
    if (desktopInput) inputs.push(desktopInput);
    if (mobileInput) inputs.push(mobileInput);
    return inputs;
}

function getItemTotalEls(itemId) {
    const els = [];
    const desktopEl = document.getElementById(`item-total-${itemId}`);
    const mobileEl = document.getElementById(`item-total-mobile-${itemId}`);
    if (desktopEl) els.push(desktopEl);
    if (mobileEl) els.push(mobileEl);
    return els;
}

function changeQuantity(itemId, delta) {
    const inputs = getQtyInputs(itemId);
    if (inputs.length === 0) return;
    
    // Use the first input to determine current value
    let currentQty = parseInt(inputs[0].value);
    let newQty = currentQty + delta;
    
    // Prevent going below 1
    if (newQty < 1) return;
    
    // 1. Optimistic UI Update (Update all inputs)
    inputs.forEach(input => {
        input.value = newQty;
        // Toggle Minus Button State locally
        const buttons = input.parentElement.querySelectorAll('button');
        buttons.forEach(btn => {
            if (btn.dataset.type === 'minus') {
                btn.disabled = (newQty <= 1);
            }
        });
    });
    
    // 3. Visual feedback (dim all totals)
    const totalEls = getItemTotalEls(itemId);
    totalEls.forEach(el => el.style.opacity = '0.5');

    // 4. Debounced Server Update
    debounce(`cart-update-${itemId}`, () => {
        updateQuantity(itemId, newQty);
    }, 500);
}

function manualUpdateQuantity(sourceInput, itemId) {
    let newQty = parseInt(sourceInput.value);
    
    // Validate
    if (isNaN(newQty) || newQty < 1) {
        newQty = 1;
        sourceInput.value = 1;
    }

    // Sync other inputs
    const inputs = getQtyInputs(itemId);
    inputs.forEach(input => {
        if (input !== sourceInput) {
            input.value = newQty;
        }
        
        // Update button states
        const buttons = input.parentElement.querySelectorAll('button');
        buttons.forEach(btn => {
            if (btn.dataset.type === 'minus') {
                btn.disabled = (newQty <= 1);
            }
        });
    });

    // Dim opacity to show loading
    const totalEls = getItemTotalEls(itemId);
    totalEls.forEach(el => el.style.opacity = '0.5');

    // Trigger update immediately
    updateQuantity(itemId, newQty);
}

async function updateQuantity(itemId, quantity) {
    const totalEls = getItemTotalEls(itemId);
    const inputs = getQtyInputs(itemId);
    
    try {
        const response = await axios.put(`{{ url('/cart/update') }}/${itemId}`, { quantity: quantity });
        if (response.data.success) {
            const cart = response.data.data.cart;
            
            // Update item totals
            const returnedItem = cart.items.find(i => i.id == itemId);
            if (returnedItem) {
                totalEls.forEach(el => {
                    el.textContent = formatCurrency(returnedItem.total);
                    el.style.opacity = '1';
                });
            }
            
            // Update summary
            updateSummary(cart);
            
            // Update header cart count
            if (typeof updateCartCount === 'function') {
                updateCartCount(response.data.data.cart_count);
            }
        }
    } catch (error) {
        console.error(error);
        // Revert on error
        const msg = error.response?.data?.message || 'Failed to update quantity';
        alert(msg);
        // Optional: reload or revert value
    } finally {
        totalEls.forEach(el => el.style.opacity = '1');
    }
}

function updateSummary(cart) {
    const subtotalEl = document.getElementById('summary-subtotal');
    if (subtotalEl) subtotalEl.textContent = formatCurrency(cart.subtotal);
    
    const discountEl = document.getElementById('summary-discount');
    if (discountEl && cart.discount_total > 0) {
        discountEl.textContent = '-' + formatCurrency(cart.discount_total);
        discountEl.parentElement.classList.remove('hidden');
    } else if (discountEl) {
        discountEl.parentElement.classList.add('hidden');
    }
    
    const taxEl = document.getElementById('summary-tax');
    if (taxEl) taxEl.textContent = formatCurrency(cart.tax_total);
    
    const shippingEl = document.getElementById('summary-shipping');
    if (shippingEl) {
        shippingEl.textContent = 'Calculated at checkout';
        shippingEl.classList.remove('text-green-500');
        shippingEl.classList.add('text-dark');
    }
    
    const totalEl = document.getElementById('summary-total');
    if (totalEl) totalEl.textContent = formatCurrency(cart.grand_total);
    
    // Header count
    const headerCount = document.getElementById('cart-header-count');
    if (headerCount) {
        headerCount.textContent = `${cart.items_count} Item${cart.items_count !== 1 ? 's' : ''}`;
    }
}

async function removeItem(itemId) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to remove this item from your cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        customClass: {
            popup: 'text-sm sm:text-base',
            title: 'text-lg sm:text-xl',
            htmlContainer: 'text-sm'
        }
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.delete(`{{ url('/cart/remove') }}/${itemId}`);
            if (response.data.success) {
                const row = document.querySelector(`[data-item-row="${itemId}"]`);
                if (row) {
                    row.remove();
                }
                
                if (response.data.data.cart_count === 0) {
                    window.location.reload();
                } else {
                    updateSummary(response.data.data.cart);
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(response.data.data.cart_count);
                    }
                    
                    Swal.fire({
                        title: 'Removed!',
                        text: 'Item has been removed from your cart.',
                        icon: 'success',
                        customClass: {
                            popup: 'text-sm sm:text-base',
                            title: 'text-lg sm:text-xl'
                        }
                    });
                }
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                title: 'Error!',
                text: error.response?.data?.message || 'Failed to remove item',
                icon: 'error',
                customClass: {
                    popup: 'text-sm sm:text-base',
                    title: 'text-lg sm:text-xl'
                }
            });
        }
    }
}

// Listen for cart updates from other tabs to refresh the cart page
const cartPageChannel = new BroadcastChannel('cart_updates');
cartPageChannel.onmessage = (event) => {
    if (event.data.type === 'cart_updated') {
        // Only reload if the update came from a DIFFERENT tab
        if (event.data.sourceTabId && event.data.sourceTabId !== window.TAB_ID) {
            window.location.reload();
        }
    }
};

// Prevent double-tap zoom on buttons for better mobile UX
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('button, a');
    buttons.forEach(button => {
        button.addEventListener('touchend', function(e) {
            e.preventDefault();
            this.click();
        }, { passive: false });
    });
});
</script>
@endpush