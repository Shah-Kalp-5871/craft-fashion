@extends('customer.layouts.master')

@section('title', 'Your Shopping Cart | ' . config('constants.SITE_NAME'))

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════ --}}
<section class="relative py-10 sm:py-14 md:py-16 bg-gradient-to-br from-primary/10 via-white to-primary/5 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-40 h-40 sm:w-72 sm:h-72 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-56 h-56 sm:w-96 sm:h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl mx-auto text-center">

            <div class="inline-flex items-center bg-primary/10 text-dark px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium mb-4 sm:mb-6">
                <i class="fas fa-shopping-bag mr-2"></i>
                Your Shopping Journey
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-playfair text-dark mb-3 sm:mb-5 leading-tight">
                Your <span class="text-primary">Shopping Cart</span>
            </h1>

            <p class="text-sm sm:text-base md:text-lg text-secondary max-w-xl mx-auto leading-relaxed px-2">
                Review your selected items and proceed to checkout
            </p>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════
     FLASH MESSAGE
═══════════════════════════════════════════════ --}}
<div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    @if(session('message'))
        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-2xl mb-4 flex items-start gap-3 text-sm sm:text-base" data-aos="fade-down">
            <i class="fas fa-check-circle text-green-500 text-lg mt-0.5 flex-shrink-0"></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif
</div>


{{-- ═══════════════════════════════════════════════
     CART CONTENT
═══════════════════════════════════════════════ --}}
<section class="pb-10 sm:pb-14 md:pb-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        @if(empty($cart['items']))

            {{-- ─── EMPTY CART ─── --}}
            <div class="text-center py-14 sm:py-20 px-4" data-aos="fade-up">
                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-5 sm:mb-6">
                    <i class="fas fa-cart-shopping text-4xl sm:text-5xl text-primary"></i>
                </div>
                <h3 class="text-2xl sm:text-3xl font-bold font-playfair text-dark mb-3">Your Cart is Empty</h3>
                <p class="text-sm sm:text-base text-secondary mb-7 max-w-sm mx-auto">
                    Looks like you haven't added anything yet. Let's find something beautiful for you!
                </p>
                <div class="flex flex-col xs:flex-row justify-center gap-3 sm:gap-4">
                    <a href="{{ route('customer.products.list') }}"
                       class="bg-primary text-white px-7 py-3.5 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 inline-flex items-center justify-center text-sm sm:text-base min-h-[48px]">
                        <i class="fas fa-bag-shopping mr-2"></i> Start Shopping
                    </a>
                    <a href="{{ config('constants.WHATSAPP_LINK') }}"
                       class="bg-white text-dark border border-primary px-7 py-3.5 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary hover:text-white inline-flex items-center justify-center text-sm sm:text-base min-h-[48px]">
                        <i class="fab fa-whatsapp mr-2 text-green-500"></i> Get Style Advice
                    </a>
                </div>
            </div>

        @else

            @php
                $subtotal   = $cart['subtotal'];
                $shipping   = $cart['shipping_total'];
                $total      = $cart['grand_total'];
                $itemCount  = $cart['items_count'];
            @endphp


            {{-- ════════════════════════════════════════
                 MOBILE ORDER SUMMARY STRIP (< lg)
                 Shows total + checkout button at top on mobile
            ════════════════════════════════════════ --}}
            <div class="lg:hidden mb-5">
                <div class="bg-primary/5 border border-primary/20 rounded-2xl px-4 py-3 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs text-secondary mb-0.5">Total (<span id="mobile-strip-count">{{ $itemCount }}</span> item{{ $itemCount > 1 ? 's' : '' }})</p>
                        <p id="mobile-strip-total" class="text-lg font-bold text-primary">₹{{ number_format($total, 2) }}</p>
                    </div>
                    <a href="{{ route('customer.checkout.index') }}"
                       class="bg-primary text-white px-5 py-2.5 rounded-full font-semibold text-sm inline-flex items-center gap-2 hover:bg-primary/90 transition-all duration-200 active:scale-95 min-h-[44px] whitespace-nowrap">
                        Checkout <i class="fas fa-lock text-xs"></i>
                    </a>
                </div>
            </div>


            {{-- ════════════════════════════════════════
                 MAIN GRID  (items | summary)
            ════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">


                {{-- ─── CART ITEMS COLUMN ─── --}}
                <div class="lg:col-span-2" data-aos="fade-right">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-md border border-primary/10 overflow-hidden">

                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-primary/5 to-primary/10 px-4 sm:px-6 py-3.5 border-b border-primary/20 flex items-center justify-between">
                            <h2 class="text-base sm:text-xl font-bold font-playfair text-dark">Your Items</h2>
                            <span id="cart-header-count"
                                  class="bg-primary text-white px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                                {{ $itemCount }} Item{{ $itemCount > 1 ? 's' : '' }}
                            </span>
                        </div>

                        <div class="p-3 sm:p-4 md:p-6">

                            {{-- ── ITEMS LIST ── --}}
                            <div id="cart-items-list" class="divide-y divide-gray-100">

                                @foreach($cart['items'] as $item)

                                    {{-- ╔══════════════════════════════╗
                                         ║  SINGLE CART ITEM CARD        ║
                                         ╚══════════════════════════════╝ --}}
                                    <div class="py-4 first:pt-0 last:pb-0 group"
                                         data-item-row="{{ $item['id'] }}">

                                        <div class="flex gap-3 sm:gap-4">

                                            {{-- Product Thumbnail --}}
                                            <div class="flex-shrink-0">
                                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                                                    <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}"
                                                         alt="{{ $item['product_name'] ?? 'Product' }}"
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                </div>
                                            </div>

                                            {{-- Product Info + Controls --}}
                                            <div class="flex-1 min-w-0 flex flex-col justify-between gap-2">

                                                {{-- Name + Remove (top row) --}}
                                                <div class="flex items-start justify-between gap-2">
                                                    <h3 class="font-bold text-dark text-sm sm:text-base leading-snug line-clamp-2 flex-1">
                                                        {{ $item['product_name'] ?? 'Product' }}
                                                    </h3>
                                                    {{-- Remove – always visible, icon-only on xs --}}
                                                    <button type="button"
                                                            onclick="removeItem('{{ $item['id'] }}')"
                                                            class="flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-50 active:scale-95 min-w-[36px] min-h-[36px] flex items-center justify-center"
                                                            aria-label="Remove item">
                                                        <i class="fas fa-trash-alt text-xs sm:text-sm"></i>
                                                    </button>
                                                </div>

                                                {{-- Variant Badges --}}
                                                @if(!empty($item['attributes']))
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($item['attributes'] as $name => $value)
                                                            <span class="bg-gray-100 text-secondary text-[10px] sm:text-xs px-2 py-0.5 rounded-md font-medium">
                                                                {{ $name }}: {{ $value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Price + Qty (bottom row) --}}
                                                <div class="flex items-center justify-between gap-2 mt-auto">

                                                    {{-- Qty Stepper --}}
                                                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                                        <button type="button"
                                                                data-type="minus"
                                                                onclick="changeQuantity('{{ $item['id'] }}', -1)"
                                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                                                class="quantity-control w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 active:bg-gray-200 transition-colors disabled:opacity-40 disabled:cursor-not-allowed min-w-[36px]"
                                                                aria-label="Decrease quantity">
                                                            <i class="fas fa-minus text-xs text-dark"></i>
                                                        </button>

                                                        <input type="number"
                                                               id="qty-input-mobile-{{ $item['id'] }}"
                                                               value="{{ $item['quantity'] }}"
                                                               min="1"
                                                               class="w-10 sm:w-12 px-0 py-2 border-0 text-center text-sm font-semibold focus:ring-0 focus:outline-none bg-white quantity-input
                                                                      appearance-none [-moz-appearance:textfield]
                                                                      [&::-webkit-outer-spin-button]:appearance-none
                                                                      [&::-webkit-inner-spin-button]:appearance-none"
                                                               onchange="manualUpdateQuantity(this, '{{ $item['id'] }}')"
                                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                               aria-label="Quantity">

                                                        <button type="button"
                                                                data-type="plus"
                                                                onclick="changeQuantity('{{ $item['id'] }}', 1)"
                                                                class="quantity-control w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 active:bg-gray-200 transition-colors min-w-[36px]"
                                                                aria-label="Increase quantity">
                                                            <i class="fas fa-plus text-xs text-dark"></i>
                                                        </button>
                                                    </div>

                                                    {{-- Item price + total --}}
                                                    <div class="text-right">
                                                        <div id="item-total-{{ $item['id'] }}"
                                                             class="text-sm sm:text-base font-bold text-dark transition-opacity duration-200">
                                                            ₹{{ number_format($item['total'], 2) }}
                                                        </div>
                                                        @if($item['quantity'] > 1)
                                                            <div class="text-[10px] sm:text-xs text-secondary">
                                                                ₹{{ number_format($item['unit_price'], 2) }} each
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>{{-- /Price+Qty row --}}

                                            </div>{{-- /product info --}}

                                        </div>{{-- /flex row --}}

                                        {{-- Shipping badge (optional per-item) --}}
                                        @if(isset($item['free_shipping']) && $item['free_shipping'])
                                            <div class="mt-2 ml-[92px] sm:ml-[108px]">
                                                <span class="inline-flex items-center gap-1 text-green-600 text-[10px] sm:text-xs font-medium">
                                                    <i class="fas fa-truck"></i> Free shipping
                                                </span>
                                            </div>
                                        @endif

                                    </div>{{-- /item card --}}

                                @endforeach

                            </div>{{-- /cart-items-list --}}

                            {{-- ── Continue Shopping ── --}}
                            <div class="mt-5 pt-4 border-t border-gray-100">
                                <a href="{{ route('customer.products.list') }}"
                                   class="inline-flex items-center text-primary hover:text-primary/80 transition-colors font-semibold text-sm sm:text-base group">
                                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-sm"></i>
                                    Continue Shopping
                                </a>
                            </div>

                        </div>{{-- /card body --}}

                    </div>{{-- /cart items card --}}
                </div>{{-- /lg:col-span-2 --}}


                {{-- ─── ORDER SUMMARY COLUMN ─── --}}
                <div class="lg:col-span-1 lg:sticky lg:top-24 h-fit" data-aos="fade-left">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-md border border-primary/10 p-4 sm:p-6">

                        <h2 class="text-lg sm:text-2xl font-bold font-playfair text-dark mb-4 sm:mb-5">Order Summary</h2>

                        {{-- Pricing rows --}}
                        <div class="space-y-2.5 sm:space-y-3 mb-5">

                            <div class="flex justify-between items-center text-sm sm:text-base">
                                <span class="text-secondary">Subtotal</span>
                                <span id="summary-subtotal" class="font-semibold text-dark">₹{{ number_format($subtotal, 2) }}</span>
                            </div>

                            @if($cart['discount_total'] > 0)
                            <div id="discount-row" class="flex justify-between items-center text-sm sm:text-base text-green-600">
                                <span class="flex items-center gap-1.5"><i class="fas fa-tag text-xs"></i> Discount</span>
                                <span id="summary-discount" class="font-semibold">-₹{{ number_format($cart['discount_total'], 2) }}</span>
                            </div>
                            @else
                            <div id="discount-row" class="hidden flex justify-between items-center text-sm sm:text-base text-green-600">
                                <span class="flex items-center gap-1.5"><i class="fas fa-tag text-xs"></i> Discount</span>
                                <span id="summary-discount" class="font-semibold"></span>
                            </div>
                            @endif

                            <div class="flex justify-between items-center text-sm sm:text-base">
                                <span class="text-secondary">Tax</span>
                                <span id="summary-tax" class="text-dark font-medium">₹{{ number_format($cart['tax_total'], 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center text-sm sm:text-base">
                                <span class="text-secondary">Shipping</span>
                                <span id="summary-shipping" class="font-medium text-dark">Calculated at checkout</span>
                            </div>

                            @if($shipping > 0)
                            <div class="bg-primary/5 border border-primary/20 rounded-xl p-2.5 text-center">
                                <p class="text-primary text-xs sm:text-sm font-medium">
                                    <i class="fas fa-truck mr-1"></i> Free shipping on orders above ₹1000!
                                </p>
                            </div>
                            @endif

                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                                <span class="font-bold text-dark text-sm sm:text-base">Total</span>
                                <span id="summary-total" class="font-bold text-primary text-lg sm:text-xl">₹{{ number_format($total, 2) }}</span>
                            </div>

                        </div>{{-- /pricing rows --}}

                        {{-- Checkout CTA --}}
                        <a href="{{ route('customer.checkout.index') }}"
                           class="block w-full bg-primary text-white py-3.5 sm:py-4 rounded-xl sm:rounded-2xl font-semibold text-center text-sm sm:text-base
                                  transition-all duration-300 hover:bg-primary/90 hover:shadow-xl hover:scale-[1.02] active:scale-100
                                  flex items-center justify-center gap-2 group min-h-[52px]">
                            <span>Proceed to Checkout</span>
                            <i class="fas fa-lock text-xs group-hover:scale-110 transition-transform"></i>
                        </a>

                        {{-- Trust badges --}}
                        <div class="mt-4 flex items-center justify-center gap-4 text-secondary text-[10px] sm:text-xs">
                            <span class="flex items-center gap-1"><i class="fas fa-shield-alt text-green-500"></i> Secure</span>
                            <span class="flex items-center gap-1"><i class="fas fa-lock text-blue-500"></i> Encrypted</span>
                            <span class="flex items-center gap-1"><i class="fas fa-undo text-primary"></i> Easy returns</span>
                        </div>

                    </div>
                </div>{{-- /summary column --}}

            </div>{{-- /grid --}}

        @endif {{-- end cart has items --}}

    </div>{{-- /container --}}
</section>


{{-- ═══════════════════════════════════════════════
     FEATURES BANNER
═══════════════════════════════════════════════ --}}
<section class="py-10 sm:py-14 md:py-16 bg-gradient-to-r from-primary to-primary/90 text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 md:gap-8">

            <div data-aos="fade-up" class="p-3 sm:p-5 text-center">
                <div class="w-11 h-11 sm:w-14 sm:h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fas fa-truck text-base sm:text-xl"></i>
                </div>
                <h3 class="text-xs sm:text-base font-bold mb-1">Fast Delivery</h3>
                <p class="text-white/75 text-[10px] sm:text-sm leading-snug">Express shipping across India</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="100" class="p-3 sm:p-5 text-center">
                <div class="w-11 h-11 sm:w-14 sm:h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fas fa-undo text-base sm:text-xl"></i>
                </div>
                <h3 class="text-xs sm:text-base font-bold mb-1">Easy Returns</h3>
                <p class="text-white/75 text-[10px] sm:text-sm leading-snug">7-day return policy</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="200" class="p-3 sm:p-5 text-center">
                <div class="w-11 h-11 sm:w-14 sm:h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fas fa-shield-alt text-base sm:text-xl"></i>
                </div>
                <h3 class="text-xs sm:text-base font-bold mb-1">Secure Payment</h3>
                <p class="text-white/75 text-[10px] sm:text-sm leading-snug">100% secure transactions</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="300" class="p-3 sm:p-5 text-center">
                <div class="w-11 h-11 sm:w-14 sm:h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fas fa-award text-base sm:text-xl"></i>
                </div>
                <h3 class="text-xs sm:text-base font-bold mb-1">Premium Quality</h3>
                <p class="text-white/75 text-[10px] sm:text-sm leading-snug">Exquisite boutique designs</p>
            </div>

        </div>
    </div>
</section>

@endsection


@push('scripts')
<script>
/* ──────────────────────────────────────────────────────────
   UTILITY
────────────────────────────────────────────────────────── */
const formatCurrency = (amount) =>
    '₹' + parseFloat(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

const debounceTimers = {};
function debounce(key, fn, delay = 500) {
    clearTimeout(debounceTimers[key]);
    debounceTimers[key] = setTimeout(fn, delay);
}

/* ──────────────────────────────────────────────────────────
   DOM HELPERS  (unified desktop + mobile selectors)
────────────────────────────────────────────────────────── */
function getQtyInputs(itemId) {
    return [
        document.getElementById(`qty-input-mobile-${itemId}`),
        document.getElementById(`qty-input-${itemId}`)
    ].filter(Boolean);
}

function getItemTotalEls(itemId) {
    return [
        document.getElementById(`item-total-${itemId}`),
        document.getElementById(`item-total-mobile-${itemId}`)
    ].filter(Boolean);
}

/* ──────────────────────────────────────────────────────────
   QUANTITY CONTROLS
────────────────────────────────────────────────────────── */
function changeQuantity(itemId, delta) {
    const inputs = getQtyInputs(itemId);
    if (!inputs.length) return;

    let newQty = parseInt(inputs[0].value) + delta;
    if (newQty < 1) return;

    // optimistic UI
    inputs.forEach(inp => {
        inp.value = newQty;
        inp.closest('.flex')?.querySelectorAll('button[data-type="minus"]').forEach(btn => {
            btn.disabled = newQty <= 1;
        });
    });

    getItemTotalEls(itemId).forEach(el => el.style.opacity = '0.4');

    debounce(`qty-${itemId}`, () => updateQuantity(itemId, newQty));
}

function manualUpdateQuantity(sourceInput, itemId) {
    let newQty = parseInt(sourceInput.value);
    if (isNaN(newQty) || newQty < 1) { newQty = 1; sourceInput.value = 1; }

    getQtyInputs(itemId).forEach(inp => {
        inp.value = newQty;
        inp.closest('.flex')?.querySelectorAll('button[data-type="minus"]').forEach(btn => {
            btn.disabled = newQty <= 1;
        });
    });

    getItemTotalEls(itemId).forEach(el => el.style.opacity = '0.4');
    updateQuantity(itemId, newQty);
}

/* ──────────────────────────────────────────────────────────
   API  – UPDATE QUANTITY
────────────────────────────────────────────────────────── */
async function updateQuantity(itemId, quantity) {
    const totalEls = getItemTotalEls(itemId);
    try {
        const res = await axios.put(`{{ url('/cart/update') }}/${itemId}`, { quantity });
        if (res.data.success) {
            const cart        = res.data.data.cart;
            const returnedItem = cart.items.find(i => i.id == itemId);
            if (returnedItem) {
                totalEls.forEach(el => {
                    el.textContent = formatCurrency(returnedItem.total);
                    el.style.opacity = '1';
                });
            }
            updateSummary(cart);
            if (typeof updateCartCount === 'function') updateCartCount(res.data.data.cart_count);
        }
    } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || 'Failed to update quantity');
    } finally {
        totalEls.forEach(el => el.style.opacity = '1');
    }
}

/* ──────────────────────────────────────────────────────────
   API  – REMOVE ITEM
────────────────────────────────────────────────────────── */
async function removeItem(itemId) {
    const result = await Swal.fire({
        title: 'Remove item?',
        text: 'Do you want to remove this item from your cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        customClass: { popup: 'rounded-2xl text-sm', title: 'text-lg' }
    });

    if (!result.isConfirmed) return;

    try {
        const res = await axios.delete(`{{ url('/cart/remove') }}/${itemId}`);
        if (res.data.success) {
            const row = document.querySelector(`[data-item-row="${itemId}"]`);
            if (row) {
                row.style.transition = 'all 0.3s';
                row.style.opacity    = '0';
                row.style.height     = row.offsetHeight + 'px';
                setTimeout(() => {
                    row.style.height  = '0';
                    row.style.padding = '0';
                    row.style.margin  = '0';
                    row.style.overflow = 'hidden';
                    setTimeout(() => row.remove(), 300);
                }, 50);
            }

            if (res.data.data.cart_count === 0) {
                setTimeout(() => window.location.reload(), 400);
            } else {
                updateSummary(res.data.data.cart);
                if (typeof updateCartCount === 'function') updateCartCount(res.data.data.cart_count);

                setTimeout(() => Swal.fire({
                    title: 'Removed!',
                    text: 'Item removed from your cart.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl text-sm' }
                }), 350);
            }
        }
    } catch (err) {
        console.error(err);
        Swal.fire({
            title: 'Error!',
            text: err.response?.data?.message || 'Failed to remove item',
            icon: 'error',
            customClass: { popup: 'rounded-2xl text-sm' }
        });
    }
}

/* ──────────────────────────────────────────────────────────
   UPDATE SUMMARY  (sidebar + mobile strip)
────────────────────────────────────────────────────────── */
function updateSummary(cart) {
    // sidebar
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    set('summary-subtotal', formatCurrency(cart.subtotal));
    set('summary-tax',      formatCurrency(cart.tax_total));
    set('summary-total',    formatCurrency(cart.grand_total));

    const discountRow = document.getElementById('discount-row');
    if (discountRow) {
        if (cart.discount_total > 0) {
            discountRow.classList.remove('hidden');
            set('summary-discount', '-' + formatCurrency(cart.discount_total));
        } else {
            discountRow.classList.add('hidden');
        }
    }

    const shippingEl = document.getElementById('summary-shipping');
    if (shippingEl) {
        shippingEl.textContent = 'Calculated at checkout';
        shippingEl.className   = 'font-medium text-dark';
    }

    const headerCount = document.getElementById('cart-header-count');
    if (headerCount) {
        const n = cart.items_count;
        headerCount.textContent = `${n} Item${n !== 1 ? 's' : ''}`;
    }

    // mobile strip
    const stripTotal = document.getElementById('mobile-strip-total');
    if (stripTotal) stripTotal.textContent = formatCurrency(cart.grand_total);

    const stripCount = document.getElementById('mobile-strip-count');
    if (stripCount) stripCount.textContent = cart.items_count;
}

/* ──────────────────────────────────────────────────────────
   CROSS-TAB SYNC
────────────────────────────────────────────────────────── */
try {
    const cartPageChannel = new BroadcastChannel('cart_updates');
    cartPageChannel.onmessage = (e) => {
        if (e.data.type === 'cart_updated' && e.data.sourceTabId !== window.TAB_ID) {
            window.location.reload();
        }
    };
} catch (e) { /* BroadcastChannel not available in all environments */ }

/* ──────────────────────────────────────────────────────────
   MOBILE  – prevent double-tap zoom on interactive elements
────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('button.quantity-control, a[role="button"]').forEach(el => {
        el.addEventListener('touchend', function(e) {
            e.preventDefault();
            this.click();
        }, { passive: false });
    });
});
</script>
@endpush