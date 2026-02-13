@extends('customer.layouts.master')

@section('title', 'Search results for "' . $searchQuery . '" | ' . config('constants.SITE_NAME'))

@section('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    .badge-tag {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .new-badge { background: #ecfdf5; color: #059669; }
    .featured-badge { background: #fef3c7; color: #d97706; }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Search Header -->
    <div class="bg-white border-b border-gray-200 py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Search Results</h1>
            <p class="text-gray-500">Showing results for <span class="text-primary font-bold">"{{ $searchQuery }}"</span></p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-dark">Filters</h3>
                        <a href="{{ route('customer.products.list') }}" class="text-xs text-primary hover:underline">Clear All</a>
                    </div>

                    <form id="filterForm" action="{{ route('customer.products.search') }}" method="GET">
                        <input type="hidden" name="q" value="{{ $searchQuery }}">
                        
                        <!-- Categories -->
                        @if(isset($filters['categories']) && count($filters['categories']) > 0)
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-dark mb-3">Categories</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                @foreach($filters['categories'] as $cat)
                                <label class="flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="category_id" value="{{ $cat['id'] }}" 
                                            class="rounded border-gray-300 text-primary focus:ring-primary/20 w-4 h-4"
                                            {{ request('category_id') == $cat['id'] ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-600 group-hover:text-primary transition-colors">{{ $cat['name'] }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">({{ $cat['count'] }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Price Range -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-dark mb-4">Price Range</label>
                            <div class="space-y-4">
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" value="{{ $minPrice ?? '' }}" placeholder="Min" min="0" step="1"
                                        class="w-1/2 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-1 focus:ring-primary">
                                    <input type="number" name="max_price" value="{{ $maxPrice ?? '' }}" placeholder="Max" min="0" step="1"
                                        class="w-1/2 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-1 focus:ring-primary">
                                </div>
                                <button type="submit" class="w-full py-2 bg-dark text-white rounded-lg text-xs font-bold hover:bg-black transition-colors uppercase tracking-wider">
                                    Apply Price
                                </button>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-dark mb-3">Availability</label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="in_stock" value="1" 
                                    class="rounded border-gray-300 text-primary focus:ring-primary/20 w-4 h-4"
                                    {{ $inStock ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="ml-3 text-sm text-gray-600">In Stock Only</span>
                            </label>
                        </div>
                        
                        <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'newest' }}">
                    </form>
                </div>
            </aside>

            <!-- Product Grid Area -->
            <main class="lg:w-3/4">
                <!-- Toolbar -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-8 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">
                            Found <span class="text-dark font-bold">{{ $paginator['total'] ?? 0 }}</span> products
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-500 whitespace-nowrap">Sort by:</label>
                        <select onchange="window.location.href = '{{ route('customer.products.search', ['q' => $searchQuery]) }}' + '&sort_by=' + this.value"
                            class="bg-gray-50 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 text-dark font-medium cursor-pointer">
                            <option value="newest" {{ ($sortBy ?? 'newest') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                            <option value="price_asc" {{ ($sortBy ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ ($sortBy ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ ($sortBy ?? '') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>

                @if(count($products) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="product-card group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                        <div class="relative aspect-[4/5] overflow-hidden">
                            <a href="{{ route('customer.products.details', $product['slug']) }}" class="block h-full">
                                <img src="{{ Str::startsWith($product['main_image'] ?? '', 'http') ? $product['main_image'] : asset('storage/' . ($product['main_image'] ?? '')) }}" 
                                    alt="{{ $product['name'] }}" 
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    onerror="this.onerror=null;this.src='/storage/images/placeholder-product.jpg';">
                            </a>
                            
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product['is_new']) <span class="badge-tag new-badge">New</span> @endif
                                @if($product['discount_percent'] > 0) <span class="badge-tag bg-red-100 text-red-600">-{{ $product['discount_percent'] }}%</span> @endif
                            </div>

                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <div class="mb-auto">
                                <p class="text-[10px] uppercase tracking-widest text-primary font-bold mb-1">{{ $product['category_name'] ?? 'Jewellery' }}</p>
                                <a href="{{ route('customer.products.details', $product['slug']) }}">
                                    <h3 class="text-dark font-bold text-base mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $product['name'] }}</h3>
                                </a>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <div class="flex flex-col">
                                    <span class="text-xl font-bold text-dark">₹{{ number_format($product['price']) }}</span>
                                    @if($product['compare_price'] && $product['compare_price'] > $product['price'])
                                    <span class="text-xs text-gray-400 line-through">₹{{ number_format($product['compare_price']) }}</span>
                                    @endif
                                </div>
                                <button onclick="quickAddToCart(event, '{{ $product['default_variant_id'] ?? $product['id'] }}')" class="w-10 h-10 bg-dark text-white rounded-xl hover:bg-primary transition-colors">
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(isset($paginator['last_page']) && $paginator['last_page'] > 1)
                <div class="mt-12 flex justify-center">
                    <nav class="flex items-center gap-2">
                        @if($paginator['current_page'] > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $paginator['current_page'] - 1]) }}" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-primary transition-all">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                        @endif

                        @for($i = 1; $i <= $paginator['last_page']; $i++)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all
                            {{ $i == $paginator['current_page'] ? 'bg-primary text-white shadow-lg' : 'bg-white border text-gray-600 hover:text-primary' }}">
                            {{ $i }}
                        </a>
                        @endfor

                        @if($paginator['current_page'] < $paginator['last_page'])
                        <a href="{{ request()->fullUrlWithQuery(['page' => $paginator['current_page'] + 1]) }}" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-primary transition-all">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                        @endif
                    </nav>
                </div>
                @endif
                @else
                <div class="bg-white rounded-3xl p-20 text-center border border-gray-100 shadow-sm">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-search text-gray-200 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-dark mb-4">No results for "{{ $searchQuery }}"</h2>
                    <p class="text-gray-500 mb-10 max-w-sm mx-auto">Try different keywords or browse our categories to find what you're looking for.</p>
                    <a href="{{ route('customer.products.list') }}" class="inline-block px-10 py-4 bg-primary text-white rounded-full font-black shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all">
                        Explore Collections
                    </a>
                </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    async function quickAddToCart(e, id) {
        e.preventDefault();
        try {
            const res = await axios.post('{{ route('customer.cart.add') }}', { variant_id: id, quantity: 1 });
            if(res.data.success) {
                showToast('Added to bag!', 'success');
                if (typeof updateCartCount === 'function') {
                    updateCartCount(res.data.cart_count);
                }
            }
        } catch(err) {
            showToast('Failed to add to bag', 'error');
        }
    }
</script>
@endpush
```
