@extends('customer.layouts.master')

@section('title', 'Your Shopping Cart | ' . config('constants.SITE_NAME'))

@section('content')
<!-- Modern Hero Section -->
<section class="relative py-16 bg-gradient-to-br from-primary/10 via-white to-primary/5 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-6">
                <i class="fas fa-shopping-bag mr-2"></i>
                Your Shopping Journey
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold font-playfair text-dark mb-6 leading-tight">
                Your <span class="text-primary">Shopping Cart</span>
            </h1>
            
            <p class="text-xl text-secondary max-w-2xl mx-auto leading-relaxed">
                Review your selected items and proceed to checkout
            </p>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center" data-aos="fade-down">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                {{ session('message') }}
            </div>
        @endif
        
        @if(empty($cart['items']))
            <!-- Empty Cart State -->
            <div class="text-center py-20" data-aos="fade-up">
                <div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-cart-shopping text-5xl text-primary"></i>
                </div>
                <h3 class="text-3xl font-bold font-playfair text-dark mb-4">Your Cart is Empty</h3>
                <p class="text-xl text-secondary mb-8 max-w-md mx-auto">Looks like you haven't added anything to your cart yet. Let's find something beautiful for you!</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('customer.products.list') }}" class="bg-primary text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 inline-flex items-center">
                        <i class="fas fa-bag-shopping mr-3"></i>
                        <span>Start Shopping</span>
                    </a>
                    <a href="{{ config('constants.WHATSAPP_LINK') }}" class="bg-white text-dark border border-primary px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary hover:text-white inline-flex items-center">
                        <i class="fab fa-whatsapp mr-3 text-green-500"></i>
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
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-lg border border-primary/10 overflow-hidden" data-aos="fade-right">
                        <!-- Cart Header -->
                        <div class="bg-gradient-to-r from-primary/5 to-primary/10 px-6 py-4 border-b border-primary/20">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-bold font-playfair text-dark">Your Items</h2>
                                <span id="cart-header-count" class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $itemCount }} Item{{ $itemCount > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Cart Items List -->
                            <div class="space-y-6">
                                @foreach($cart['items'] as $item)
                                    <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-primary/30 transition-all duration-300 group" data-item-row="{{ $item['id'] }}">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 relative">
                                            <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['product_name'] ?? 'Product' }}" 
                                                 class="w-24 h-24 object-cover rounded-2xl shadow-md group-hover:scale-105 transition duration-300">
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-dark text-lg mb-1 truncate">{{ $item['product_name'] ?? 'Product' }}</h3>
                                            <div class="flex items-center gap-4 text-sm text-secondary mb-2">
                                                @if(!empty($item['attributes']))
                                                    @foreach($item['attributes'] as $name => $value)
                                                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $name }}: {{ $value }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                            
                                            <!-- Price and Quantity -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <!-- Price -->
                                                    <div class="text-left">
                                                        <span class="text-xl font-bold text-primary">₹{{ number_format($item['unit_price'], 2) }}</span>
                                                    </div>
                                                    
                                                    <!-- Quantity -->
                                                    <div class="flex items-center gap-2">
                                                        <label class="text-sm font-medium text-dark">Qty:</label>
                                                        <div class="flex items-center border border-gray-200 rounded-2xl overflow-hidden">
                                                            <button type="button" data-type="minus" class="quantity-control w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition" 
                                                                onclick="changeQuantity('{{ $item['id'] }}', -1)" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                                <i class="fas fa-minus text-sm"></i>
                                                            </button>
                                                            <input type="number" id="qty-input-{{ $item['id'] }}" value="{{ $item['quantity'] }}" readonly
                                                                   class="w-12 px-0 py-2 border-0 text-center focus:ring-0 focus:outline-none bg-transparent quantity-input">
                                                            <button type="button" data-type="plus" class="quantity-control w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition"
                                                                onclick="changeQuantity('{{ $item['id'] }}', 1)">
                                                                <i class="fas fa-plus text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Item Total & Remove -->
                                                <div class="text-right">
                                                    <div id="item-total-{{ $item['id'] }}" class="text-lg font-bold text-dark mb-2">₹{{ number_format($item['total'], 2) }}</div>
                                                    <button type="button" onclick="removeItem('{{ $item['id'] }}')" 
                                                       class="text-red-500 hover:text-red-700 transition duration-300 flex items-center text-sm font-medium">
                                                        <i class="fas fa-trash mr-1"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Cart Actions -->
                            <div class="flex justify-start items-center mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('customer.products.list') }}" class="text-primary hover:text-primary/80 transition duration-300 flex items-center font-semibold group">
                                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-lg border border-primary/10 p-6 sticky top-6" data-aos="fade-left">
                        <h2 class="text-2xl font-bold font-playfair text-dark mb-6">Order Summary</h2>
                        
                        <!-- Pricing Breakdown -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-secondary">Subtotal</span>
                                <span id="summary-subtotal" class="font-semibold text-dark">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            @if($cart['discount_total'] > 0)
                            <div class="flex justify-between items-center py-2 text-green-600">
                                <span class="">Discount</span>
                                <span id="summary-discount" class="font-semibold">-₹{{ number_format($cart['discount_total'], 2) }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-center py-2 font-medium">
                                <span class="text-secondary">Tax Total</span>
                                <span id="summary-tax" class="text-dark">₹{{ number_format($cart['tax_total'], 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-secondary">Shipping</span>
                                <span id="summary-shipping" class="font-semibold {{ $shipping == 0 ? 'text-green-500' : 'text-dark' }}">
                                    @if($shipping == 0)
                                        <span class="flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i> FREE
                                        </span>
                                    @else
                                        ₹{{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            
                            @if($shipping > 0)
                            <div class="bg-primary/5 border border-primary/20 rounded-2xl p-3 text-center">
                                <p class="text-primary text-sm font-medium">Free shipping on orders above ₹1000!</p>
                            </div>
                            @endif
                            
                            <div class="border-t border-gray-200 pt-4 flex justify-between items-center text-lg font-bold">
                                <span class="text-dark">Total Amount</span>
                                <span id="summary-total" class="text-primary text-xl">₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Checkout Actions -->
                        <div class="space-y-4">
                            <a href="{{ route('customer.checkout.index') }}" 
                               class="block w-full bg-primary text-white py-4 rounded-2xl font-semibold text-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 flex items-center justify-center group">
                                <span>Proceed to Checkout</span>
                                <i class="fas fa-lock ml-3 group-hover:scale-110 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Features Banner -->
<section class="py-16 bg-gradient-to-r from-primary to-primary/90 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div data-aos="fade-up" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                <p class="text-white/80">Express shipping across India</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="100" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Easy Returns</h3>
                <p class="text-white/80">7-day return policy</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="200" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
                <p class="text-white/80">100% secure transactions</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="300" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Premium Quality</h3>
                <p class="text-white/80">Exquisite boutique designs</p>
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

function changeQuantity(itemId, delta) {
    const input = document.getElementById(`qty-input-${itemId}`);
    if (!input) return;
    
    let currentQty = parseInt(input.value);
    let newQty = currentQty + delta;
    
    // Prevent going below 1
    if (newQty < 1) return;
    
    // 1. Optimistic UI Update
    input.value = newQty;
    
    // 2. Toggle Minus Button State locally
    const buttons = input.parentElement.querySelectorAll('button');
    buttons.forEach(btn => {
        if (btn.dataset.type === 'minus') {
            btn.disabled = (newQty <= 1);
        }
    });
    
    // 3. Visual feedback (optional: dim the total to show it's stale)
    const itemTotalEl = document.getElementById(`item-total-${itemId}`);
    if(itemTotalEl) itemTotalEl.style.opacity = '0.5';

    // 4. Debounced Server Update
    debounce(`cart-update-${itemId}`, () => {
        updateQuantity(itemId, newQty);
    }, 500);
}

async function updateQuantity(itemId, quantity) {
    const input = document.getElementById(`qty-input-${itemId}`);
    const itemTotalEl = document.getElementById(`item-total-${itemId}`);
    
    try {
        const response = await axios.put(`{{ url('/cart/update') }}/${itemId}`, { quantity: quantity });
        if (response.data.success) {
            const cart = response.data.data.cart;
            
            // Update item total
            if (itemTotalEl) {
                const returnedItem = cart.items.find(i => i.id == itemId);
                if (returnedItem) {
                    itemTotalEl.textContent = formatCurrency(returnedItem.total);
                    itemTotalEl.style.opacity = '1';
                }
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
        if (input) {
            // We might want to fetch the fresh cart or just decrement/increment back
            // For now, simpler to alert and reload or let user try again. 
            // Better UX:
             alert(error.response?.data?.message || 'Failed to update quantity');
             // Optionally reload to sync state
             // window.location.reload(); 
        }
    } finally {
        if(itemTotalEl) itemTotalEl.style.opacity = '1';
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
        if (cart.shipping_total === 0) {
            shippingEl.innerHTML = '<span class="flex items-center text-green-500"><i class="fas fa-check-circle mr-1"></i> FREE</span>';
        } else {
            shippingEl.textContent = formatCurrency(cart.shipping_total);
            shippingEl.classList.remove('text-green-500');
        }
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
    if (!confirm('Are you sure you want to remove this item?')) return;
    
    try {
        const response = await axios.delete(`{{ url('/cart/remove') }}/${itemId}`);
        if (response.data.success) {
            const row = document.querySelector(`[data-item-row="${itemId}"]`);
            if (row) {
                row.remove();
            }
            
            if (response.data.data.cart_count === 0) {
                window.location.reload(); // Reload to show empty cart state
            } else {
                updateSummary(response.data.data.cart);
                if (typeof updateCartCount === 'function') {
                    updateCartCount(response.data.data.cart_count);
                }
            }
        }
    } catch (error) {
        console.error(error);
        alert(error.response?.data?.message || 'Failed to remove item');
    }
}
</script>
@endpush
