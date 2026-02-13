@extends('customer.layouts.master')

@section('title', $title ?? 'Exquisite Rugs Collection | ' . config('constants.SITE_NAME'))
@section('description', $meta_description ?? 'Discover our curated collection of handcrafted and modern rugs.')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Premium Rugs Hero -->
    <section class="relative min-h-[60vh] flex items-center overflow-hidden bg-gray-900 text-white">
        <div class="absolute inset-0 opacity-50">
            <img src="https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?auto=format&fit=crop&q=80" 
                 alt="Rugs Collection" 
                 class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/40 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10 w-full">
            <div class="max-w-2xl animate-fade-in-up">
                <div class="inline-flex items-center gap-3 mb-6">
                    <div class="w-12 h-1 bg-primary"></div>
                    <span class="text-sm font-black uppercase tracking-[0.3em] text-primary">The Art of Flooring</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black mb-8 leading-tight">Handcrafted <br><span class="text-primary italic font-serif">Masterpieces</span></h1>
                <p class="text-xl text-gray-300 mb-10 leading-relaxed font-light">
                    Elevate your space with our exclusive collection of hand-woven, sustainable, and designer rugs. Each piece tells a story of heritage and modern craftsmanship.
                </p>
                <div class="flex flex-wrap gap-6">
                    <a href="#collection" class="px-10 py-5 bg-white text-gray-900 rounded-2xl font-black hover:bg-primary hover:text-white transition-all transform active:scale-95 shadow-2xl">
                        Explore Collection
                    </a>
                    <a href="{{ config('constants.WHATSAPP_LINK') }}" class="px-10 py-5 bg-transparent border-2 border-white/30 text-white rounded-2xl font-black hover:bg-white/10 transition-all backdrop-blur-md">
                        Custom Orders
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories / Navigation -->
    <section class="py-12 border-b border-gray-100 sticky top-20 bg-white/80 backdrop-blur-xl z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between overflow-x-auto no-scrollbar gap-8">
                <div class="flex gap-10 whitespace-nowrap">
                    <a href="{{ route('customer.products.list') }}" class="text-sm font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-colors">All Products</a>
                    <a href="#" class="text-sm font-black uppercase tracking-widest text-primary border-b-2 border-primary pb-2">Rugs Collection</a>
                    @if(isset($filters['categories']))
                        @foreach(array_slice($filters['categories'], 0, 5) as $cat)
                            <a href="{{ route('customer.category.products', $cat['slug']) }}" class="text-sm font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-colors">{{ $cat['name'] }}</a>
                        @endforeach
                    @endif
                </div>
                
                <div class="hidden md:flex items-center gap-4 text-gray-400">
                    <span class="text-xs font-bold uppercase tracking-widest">Share:</span>
                    <a href="#" class="hover:text-primary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section id="collection" class="py-24">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-xl">
                    <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tight">Curated Designs</h2>
                    <p class="text-gray-500 font-medium">Showing {{ $paginator['total'] ?? 0 }} unique pieces found in our signature collection.</p>
                </div>
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <select onchange="updateSorting(this.value)" class="flex-grow md:flex-none bg-gray-50 border-none rounded-2xl px-8 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 cursor-pointer">
                        <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Latest Arrival</option>
                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Bestsellers</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Filters Sidebar -->
                <aside class="space-y-12">
                    <!-- Price Filter -->
                    <div class="bg-gray-50 rounded-[2rem] p-8">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-8">Price Filter</h3>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <input type="number" id="minPrice" placeholder="Min" value="{{ request('min_price') }}" min="0" step="1" class="w-full bg-white border-none rounded-2xl px-4 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/20">
                                <span class="text-gray-300">—</span>
                                <input type="number" id="maxPrice" placeholder="Max" value="{{ request('max_price') }}" min="0" step="1" class="w-full bg-white border-none rounded-2xl px-4 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/20">
                            </div>
                            <button onclick="applyPriceFilter()" class="w-full bg-gray-900 text-white rounded-2xl py-4 font-black hover:bg-primary transition-all shadow-xl active:scale-95">
                                Refine List
                            </button>
                        </div>
                    </div>

                    <!-- Dynamic Attributes -->
                    @if(isset($filters['attributes']))
                        @foreach($filters['attributes'] as $attribute)
                        <div class="bg-gray-50 rounded-[2rem] p-8">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-8">{{ $attribute['name'] }}</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($attribute['values'] as $value)
                                    @if($attribute['type'] === 'color')
                                        <button onclick="updateFilter('attribute_value', '{{ $value['id'] }}')" 
                                            class="w-10 h-10 rounded-full border-2 transition-all hover:scale-110 {{ request('attribute_value') == $value['id'] ? 'border-primary ring-4 ring-primary/20 scale-110' : 'border-white shadow-sm' }}"
                                            style="background-color: {{ $value['color_code'] }}"
                                            title="{{ $value['label'] }}">
                                        </button>
                                    @else
                                        <button onclick="updateFilter('attribute_value', '{{ $value['id'] }}')"
                                            class="px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest border-2 transition-all {{ request('attribute_value') == $value['id'] ? 'bg-primary border-primary text-white shadow-lg' : 'bg-white border-transparent text-gray-900 hover:border-primary/30' }}">
                                            {{ $value['label'] }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                            @if(request('attribute_value'))
                                <button onclick="clearFilter('attribute_value')" class="mt-6 text-[10px] font-black uppercase tracking-tighter text-gray-400 hover:text-primary transition-colors">Reset {{ $attribute['name'] }}</button>
                            @endif
                        </div>
                        @endforeach
                    @endif

                    <!-- Brands -->
                    @if(isset($filters['brands']) && count($filters['brands']) > 0)
                    <div class="bg-gray-50 rounded-[2rem] p-8">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-8">Brands</h3>
                        <div class="space-y-4">
                            @foreach($filters['brands'] as $brand)
                            <button onclick="updateFilter('brand_id', '{{ $brand['id'] }}')" 
                                class="w-full flex items-center justify-between group">
                                <span class="text-sm font-bold transition-colors {{ request('brand_id') == $brand['id'] ? 'text-primary' : 'text-gray-600 group-hover:text-primary' }}">
                                    {{ $brand['name'] }}
                                </span>
                                <span class="text-[10px] font-black text-gray-300 group-hover:text-primary">{{ $brand['count'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Trust Banner -->
                    <div class="bg-gray-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                        <div class="relative z-10">
                            <i class="fas fa-certificate text-primary text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                            <h4 class="text-xl font-black mb-4 uppercase">Lifetime Quality</h4>
                            <p class="text-gray-400 text-sm leading-relaxed mb-6">Each rug undergoes 12 levels of quality inspection before reaching your home.</p>
                            <a href="#" class="text-xs font-black uppercase tracking-widest text-primary hover:text-white transition-colors">Learn More</a>
                        </div>
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
                    </div>
                </aside>

                <!-- Product Grid -->
                <div class="md:col-span-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                        @forelse($products as $product)
                            <div class="group bg-white flex flex-col h-full animate-fade-in-up">
                                <div class="relative aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-6 shadow-sm group-hover:shadow-2xl transition-all duration-700">
                                    <a href="{{ route('customer.products.details', $product['slug']) }}" class="block h-full">
                                        <img src="{{ Str::startsWith($product['main_image'] ?? '', 'http') ? $product['main_image'] : asset('storage/' . ($product['main_image'] ?? '')) }}" 
                                             alt="{{ $product['name'] }}" 
                                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                             onerror="this.onerror=null;this.src='/storage/images/placeholder-product.jpg';">
                                    </a>
                                    
                                    @if($product['is_new'])
                                        <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md text-gray-900 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg">New</div>
                                    @endif

                                    @php
                                        $price = (float) $product['price'];
                                        $comparePrice = (float) $product['compare_price'];
                                        $discount = 0;
                                        if($comparePrice > $price && $comparePrice > 0) {
                                            $discount = round((($comparePrice - $price) / $comparePrice) * 100);
                                        }
                                    @endphp
                                    
                                    @if($discount > 0)
                                        <div class="absolute top-6 right-6 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg z-20">
                                            {{ $discount }}% OFF
                                        </div>
                                    @endif

                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center gap-4">
                                        <button onclick="quickView('{{ $product['slug'] }}')" class="w-14 h-14 bg-white text-gray-900 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all transform scale-50 group-hover:scale-100 duration-500 shadow-xl">
                                            <i class="fas fa-expand-alt"></i>
                                        </button>
                                        <button onclick="quickAddToCart(event, '{{ $product['default_variant_id'] ?? $product['id'] }}')" class="w-14 h-14 bg-white text-gray-900 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-all transform scale-50 group-hover:scale-100 duration-500 delay-75 shadow-xl">
                                            <i class="fas fa-shopping-basket"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex-grow flex flex-col">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-primary transition-colors">
                                        <a href="{{ route('customer.products.details', $product['slug']) }}">{{ $product['name'] }}</a>
                                    </h3>
                                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                                        <div class="flex flex-col">
                                            <span class="text-2xl font-black text-gray-900">₹{{ number_format($product['price']) }}</span>
                                            @if($product['compare_price'] > $product['price'])
                                                <span class="text-sm text-gray-400 line-through font-medium">₹{{ number_format($product['compare_price']) }}</span>
                                            @endif
                                        </div>
                                        <button onclick="quickAddToCart(event, '{{ $product['default_variant_id'] ?? $product['id'] }}')" class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-32 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                                <i class="fas fa-box-open text-gray-200 text-6xl mb-8"></i>
                                <h3 class="text-2xl font-black text-gray-900 mb-4">No Pieces Found</h3>
                                <p class="text-gray-400 max-w-xs mx-auto mb-10">We couldn't find any rugs matching your criteria. Try adjusting your filters.</p>
                                <a href="{{ route('customer.products.rugs') }}" class="px-10 py-5 bg-gray-900 text-white rounded-2xl font-black hover:bg-primary transition-all shadow-xl">View All Rugs</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if(isset($paginator) && $paginator['last_page'] > 1)
                        <div class="mt-20 flex justify-center gap-4">
                            @if($paginator['current_page'] > 1)
                                <a href="{{ request()->fullUrlWithQuery(['page' => $paginator['current_page'] - 1]) }}" class="w-16 h-16 bg-white border border-gray-100 rounded-2xl flex items-center justify-center text-gray-900 hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            @endif
                            
                            @for($i=1; $i<=$paginator['last_page']; $i++)
                                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="w-16 h-16 rounded-2xl flex items-center justify-center font-black transition-all shadow-sm {{ $i == $paginator['current_page'] ? 'bg-primary text-white scale-110 z-10' : 'bg-white text-gray-400 hover:text-primary border border-gray-100' }}">
                                    {{ $i }}
                                </a>
                            @endfor

                            @if($paginator['current_page'] < $paginator['last_page'])
                                <a href="{{ request()->fullUrlWithQuery(['page' => $paginator['current_page'] + 1]) }}" class="w-16 h-16 bg-white border border-gray-100 rounded-2xl flex items-center justify-center text-gray-900 hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Excellence Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 text-center">
                <div>
                    <div class="w-24 h-24 bg-white rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <i class="fas fa-leaf text-primary text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-widest">Sustainability</h4>
                    <p class="text-gray-500 leading-relaxed font-medium">100% natural fibers and eco-friendly dyes used in every weave.</p>
                </div>
                <div>
                    <div class="w-24 h-24 bg-white rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <i class="fas fa-hand-heart text-primary text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-widest">Fair Trade</h4>
                    <p class="text-gray-500 leading-relaxed font-medium">Empowering local artisanal communities through fair wages and respect.</p>
                </div>
                <div>
                    <div class="w-24 h-24 bg-white rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <i class="fas fa-shipping-fast text-primary text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-widest">Safe Passage</h4>
                    <p class="text-gray-500 leading-relaxed font-medium">Insured global delivery with specialized rug-handling experts.</p>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function updateSorting(val) {
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

    function updateFilter(name, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(name, value);
        url.searchParams.set('page', 1);
        window.location.href = url.href;
    }

    function clearFilter(name) {
        const url = new URL(window.location.href);
        url.searchParams.delete(name);
        url.searchParams.set('page', 1);
        window.location.href = url.href;
    }

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
@endsection