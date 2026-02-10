@extends('customer.layouts.master')

@section('title', ($category->name ?? 'Collection') . ' | ' . config('constants.SITE_NAME'))
@section('description', $category->meta_description ?? 'Exquisite collection of ' . ($category->name ?? 'products') . '.')

@if($category->featured_image)
    @section('og_image', asset($category->featured_image))
@endif

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Category Hero -->
        <section class="relative py-20 bg-gray-900 text-white overflow-hidden">
            <div class="absolute inset-0 opacity-40">
                <img src="{{ $category->image ? asset($category->image->file_path) : asset('assets/images/placeholder-category.jpg') }}" 
                     alt="{{ $category->name }}" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80'">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
            
            <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
                <nav class="flex justify-center text-sm text-gray-400 mb-8">
                    <a href="{{ route('customer.home.index') }}" class="hover:text-white transition-colors">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('customer.products.list') }}" class="hover:text-white transition-colors">Collections</a>
                    <span class="mx-2">/</span>
                    <span class="text-white font-medium">{{ $category->name }}</span>
                </nav>
                
                <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight animate-fade-in">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed animate-fade-in-up">
                        {{ $category->description }}
                    </p>
                @endif

                @if(isset($childCategories) && count($childCategories) > 0)
                    <div class="mt-12 flex flex-wrap justify-center gap-4">
                        @foreach($childCategories as $child)
                            <a href="{{ route('customer.category.products', $child->slug) }}" 
                               class="px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white font-bold hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- Products Section -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Toolbar -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $paginator['total'] ?? 0 }} Products</h2>
                        <p class="text-gray-500 text-sm">Showing {{ $paginator['from'] ?? 0 }} - {{ $paginator['to'] ?? 0 }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                        <select id="sortBy" onchange="updateSorting()" 
                                class="flex-grow md:flex-none px-6 py-3 bg-gray-50 border-none rounded-2xl text-gray-700 font-bold focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    <!-- Sidebar Filters -->
                    <aside class="space-y-12">
                        <!-- Related Categories -->
                        @if(isset($relatedCategories) && count($relatedCategories) > 0)
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Discover More</h3>
                                <ul class="space-y-4">
                                    @foreach($relatedCategories as $relCat)
                                        <li>
                                            <a href="{{ route('customer.category.products', $relCat->slug) }}" class="flex items-center justify-between group">
                                                <span class="text-gray-600 group-hover:text-primary transition-colors">{{ $relCat->name }}</span>
                                                <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400 group-hover:bg-primary group-hover:text-white transition-all">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Price Filter -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Price Range</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <input type="number" id="minPrice" placeholder="Min" value="{{ request('min_price') }}" min="0" step="1" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                    <span class="text-gray-400">—</span>
                                    <input type="number" id="maxPrice" placeholder="Max" value="{{ request('max_price') }}" min="0" step="1" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                                </div>
                                <button onclick="applyPriceFilter()" class="w-full py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-primary transition-all duration-300">
                                    Apply Filter
                                </button>
                            </div>
                        </div>

                    </aside>

                    <!-- Products Grid -->
                    <div class="md:col-span-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            @forelse($products as $product)
                                <div class="group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col h-full">
                                    <a href="{{ route('customer.products.details', $product['slug']) }}" class="block relative aspect-[4/5] overflow-hidden">
                                        <img src="{{ Str::startsWith($product['main_image'] ?? '', 'http') ? $product['main_image'] : asset('storage/' . ($product['main_image'] ?? '')) }}" 
                                            alt="{{ $product['name'] }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                            onerror="this.src='https://placehold.co/400x500?text=Craft+Fashion'">
                                        
                                        @if($product['discount_percent'] > 0)
                                            <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                                {{ $product['discount_percent'] }}% OFF
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center gap-3">
                                            <button onclick="quickView('{{ $product['id'] }}')" class="w-12 h-12 bg-white text-gray-900 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all transform scale-50 group-hover:scale-100 duration-500">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="quickAddToCart(event, '{{ $product['default_variant_id'] }}')" class="w-12 h-12 bg-white text-gray-900 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all transform scale-50 group-hover:scale-100 duration-500 delay-75">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        </div>
                                    </a>
                                    <div class="p-6 flex flex-col flex-grow">
                                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 hover:text-primary transition-colors text-lg flex-grow">
                                            <a href="{{ route('customer.products.details', $product['slug']) }}">{{ $product['name'] }}</a>
                                        </h3>
                                        <div class="flex items-center gap-2 mb-4">
                                            <div class="flex text-yellow-400 text-xs">
                                                @for($i=0; $i<5; $i++) <i class="fas fa-star{{ $i < floor($product['rating']) ? '' : '-half-alt' }}"></i> @endfor
                                            </div>
                                            <span class="text-xs text-gray-400">({{ $product['review_count'] }})</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <span class="text-2xl font-black text-primary">₹{{ number_format($product['price'], 0) }}</span>
                                                @if($product['compare_price'] > $product['price'])
                                                    <span class="text-sm text-gray-400 line-through font-medium">₹{{ number_format($product['compare_price'], 0) }}</span>
                                                @endif
                                            </div>
                                            <button onclick="quickAddToCart(event, '{{ $product['default_variant_id'] }}')" class="w-12 h-12 bg-gray-50 text-gray-900 rounded-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300">
                                                <i class="fas fa-shopping-basket"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-24 text-center">
                                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-300">
                                        <i class="fas fa-search text-5xl"></i>
                                    </div>
                                    <h3 class="text-3xl font-bold text-gray-900 mb-4">No Products Found</h3>
                                    <p class="text-gray-500 max-w-sm mx-auto mb-12">We couldn't find any products in this category matching your criteria.</p>
                                    <a href="{{ route('customer.products.list') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gray-900 text-white rounded-2xl font-black hover:bg-primary transition-all transform active:scale-95">
                                        Explore All Products
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if (isset($paginator) && $paginator['last_page'] > 1)
                            <div class="mt-16 flex justify-center gap-3">
                                @if ($paginator['current_page'] > 1)
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $paginator['current_page'] - 1]) }}" 
                                       class="w-14 h-14 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-900 font-bold hover:bg-primary hover:text-white hover:border-primary transition-all transform active:scale-90 shadow-sm">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @for ($i = 1; $i <= $paginator['last_page']; $i++)
                                    @if ($i == 1 || $i == $paginator['last_page'] || ($i >= $paginator['current_page'] - 1 && $i <= $paginator['current_page'] + 1))
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                                           class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold transition-all transform active:scale-90 shadow-sm {{ $i == $paginator['current_page'] ? 'bg-primary text-white scale-110 z-10' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary' }}">
                                            {{ $i }}
                                        </a>
                                    @elseif ($i == 2 || $i == $paginator['last_page'] - 1)
                                        <span class="w-14 h-14 flex items-center justify-center text-gray-400">...</span>
                                    @endif
                                @endfor

                                @if ($paginator['current_page'] < $paginator['last_page'])
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $paginator['current_page'] + 1]) }}" 
                                       class="w-14 h-14 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-900 font-bold hover:bg-primary hover:text-white hover:border-primary transition-all transform active:scale-90 shadow-sm">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter / Trust -->
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4">
                <div class="bg-gray-900 rounded-[3rem] p-12 md:p-20 relative overflow-hidden text-center text-white">
                    <div class="relative z-10 max-w-2xl mx-auto">
                        <h2 class="text-4xl md:text-5xl font-black mb-8">Can't find what you're looking for?</h2>
                        <p class="text-gray-400 text-lg mb-12">Subscribe to our newsletter and be the first to know about new arrivals in {{ $category->name }} and exclusive offers.</p>
                        
                        <form class="flex flex-col sm:flex-row gap-4">
                            <input type="email" placeholder="Your email address" class="flex-grow px-8 py-5 bg-white/10 border border-white/20 rounded-2xl focus:ring-2 focus:ring-primary/50 outline-none backdrop-blur-md">
                            <button class="px-10 py-5 bg-primary text-white rounded-2xl font-black hover:bg-primary-dark transition-all transform active:scale-95">
                                Join Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function updateSorting() {
        const val = document.getElementById('sortBy').value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort_by', val);
        url.searchParams.set('page', 1);
        window.location.href = url.href;
    }

    function applyPriceFilter() {
        const min = document.getElementById('minPrice').value;
        const max = document.getElementById('maxPrice').value;
        const url = new URL(window.location.href);
        if(min) url.searchParams.set('min_price', min); else url.searchParams.delete('min_price');
        if(max) url.searchParams.set('max_price', max); else url.searchParams.delete('max_price');
        url.searchParams.set('page', 1);
        window.location.href = url.href;
    }

    async function quickAddToCart(e, variantId) {
        e.preventDefault();
        e.stopPropagation();
        
        // Find the button to show loading state if possible
        const btn = e.currentTarget;
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await axios.post('{{ route('customer.cart.add') }}', {
                variant_id: variantId,
                quantity: 1
            });
            if (response.data.success) {
                showToast('Added to cart!', 'success');
                if (typeof updateCartCount === 'function') {
                    updateCartCount(response.data.cart_count);
                }
            } else {
                showToast(response.data.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Failed to add to cart', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    }

</script>
@endpush
