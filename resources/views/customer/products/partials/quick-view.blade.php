<div class="flex flex-col md:flex-row gap-8 p-4">
    <!-- Product Image -->
    <div class="md:w-1/2">
        <div class="aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl">
            <img src="{{ Str::startsWith($product['main_image'] ?? '', 'http') ? $product['main_image'] : asset('storage/' . ($product['main_image'] ?? '')) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x500?text=Craft+Fashion'">
        </div>
    </div>

    <!-- Product Info -->
    <div class="md:w-1/2 flex flex-col">
        <div class="mb-6">
            <p class="text-xs font-black uppercase tracking-widest text-primary mb-2">{{ $product['category_name'] ?? 'Collection' }}</p>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight mb-4">{{ $product['name'] }}</h2>
            
            <div class="flex items-center gap-4 mb-6">
                <span class="text-3xl font-black text-primary">₹{{ number_format($product['price']) }}</span>
                @if($product['compare_price'] > $product['price'])
                    <span class="text-lg text-gray-400 line-through">₹{{ number_format($product['compare_price']) }}</span>
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full">Save ₹{{ number_format($product['compare_price'] - $product['price']) }}</span>
                @endif
            </div>

            <p class="text-gray-600 leading-relaxed line-clamp-4 mb-8">
                {{ $product['short_description'] ?? 'Exquisite piece of jewellery crafted with precision and passion. A perfect addition to your collection.' }}
            </p>
        </div>

        <div class="mt-auto space-y-4">
            <div class="flex items-center gap-4">
                <div class="flex border border-gray-200 rounded-2xl overflow-hidden h-14">
                    <button class="px-6 hover:bg-gray-50 text-gray-500" onclick="updateQty(-1)">-</button>
                    <input type="number" id="quickViewQty" value="1" class="w-16 text-center border-none font-bold text-gray-900 focus:ring-0" readonly>
                    <button class="px-6 hover:bg-gray-50 text-gray-500" onclick="updateQty(1)">+</button>
                </div>
                <button onclick="addToCartFromQuickView('{{ $product['default_variant_id'] }}')" 
                        class="flex-grow h-14 bg-gray-900 text-white rounded-2xl font-black hover:bg-primary transition-all shadow-xl active:scale-95">
                    Add to Bag
                </button>
            </div>
            
            <a href="{{ route('customer.products.details', $product['slug']) }}" class="block text-center text-sm font-bold text-gray-400 hover:text-primary transition-colors py-2 uppercase tracking-widest">
                View Full Details
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<script>
    function updateQty(delta) {
        const input = document.getElementById('quickViewQty');
        let val = parseInt(input.value) + delta;
        if(val < 1) val = 1;
        input.value = val;
    }

    async function addToCartFromQuickView(variantId) {
        const qty = document.getElementById('quickViewQty').value;
        try {
            const res = await axios.post('{{ route('customer.cart.add') }}', { variant_id: variantId, quantity: qty });
            if(res.data.success) {
                showToast('Added to bag!', 'success');
                if (typeof updateCartCount === 'function') {
                    updateCartCount(res.data.cart_count);
                }
                // Close modal if possible
                if(window.closeQuickView) window.closeQuickView();
            }
        } catch(err) {
            showToast('Failed to add to bag', 'error');
        }
    }

</script>
