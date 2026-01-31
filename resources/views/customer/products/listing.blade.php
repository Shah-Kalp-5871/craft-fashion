@extends('customer.layouts.master')

@section('title', $title ?? 'All Products | ' . config('constants.SITE_NAME'))
@section('description', 'Browse our exquisite collection of kurtis, tops, kurti-bottom sets, and boutique garments.')

@section('styles')
<style>
    .filter-section {
        transition: all 0.3s ease;
    }
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
    .brand-badge { background: #f3f4f6; color: #374151; }
    .new-badge { background: #ecfdf5; color: #059669; }
    .featured-badge { background: #fef3c7; color: #d97706; }
    
    /* Price Range Slider Styling */
    input[type="range"] {
        accent-color: #c98f83;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm text-gray-500">
                <a href="{{ route('customer.home.index') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-2">/</span>
                <span class="text-dark font-medium">Shop</span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-dark">Filters</h3>
                        <a href="{{ route('customer.products.list') }}" class="text-xs text-primary hover:underline">Reset All</a>
                    </div>

                    <form id="filterForm" action="{{ route('customer.products.list') }}" method="GET">
                        <!-- Search -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-dark mb-2">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search ?? '' }}" 
                                    placeholder="Looking for something?" 
                                    class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-sm">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Categories -->
                        @if(isset($filters['categories']) && count($filters['categories']) > 0)
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-dark mb-3">Categories</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($filters['categories'] as $cat)
                                <label class="flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="category_id[]" value="{{ $cat['id'] }}" 
                                            class="rounded border-gray-300 text-primary focus:ring-primary/20 w-4 h-4"
                                            {{ in_array($cat['id'], (array)request('category_id')) ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-600 group-hover:text-primary transition-colors {{ in_array($cat['id'], (array)request('category_id')) ? 'text-primary font-semibold' : '' }}">{{ $cat['name'] }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">({{ $cat['count'] }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Brands -->
                        @if(isset($filters['brands']) && count($filters['brands']) > 0)
                        <div class="mb-8 border-t border-gray-100 pt-6">
                            <label class="block text-sm font-semibold text-dark mb-3">Brands</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($filters['brands'] as $brand)
                                <label class="flex items-center justify-between group cursor-pointer">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="brand_id[]" value="{{ $brand['id'] }}" 
                                            class="rounded border-gray-300 text-primary focus:ring-primary/20 w-4 h-4"
                                            {{ in_array($brand['id'], (array)request('brand_id')) ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-600 group-hover:text-primary transition-colors {{ in_array($brand['id'], (array)request('brand_id')) ? 'text-primary font-semibold' : '' }}">{{ $brand['name'] }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">({{ $brand['count'] }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Attributes -->
                        @if(isset($filters['attributes']))
                            @foreach($filters['attributes'] as $attribute)
                            <div class="mb-8 border-t border-gray-100 pt-6">
                                <label class="block text-sm font-semibold text-dark mb-3">{{ $attribute['name'] }}</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($attribute['values'] as $value)
                                        @if($attribute['type'] === 'color')
                                            <label class="cursor-pointer group relative" title="{{ $value['label'] }}">
                                                <input type="radio" name="attribute_value" value="{{ $value['id'] }}" 
                                                    class="hidden" 
                                                    {{ request('attribute_value') == $value['id'] ? 'checked' : '' }}
                                                    onchange="this.form.submit()">
                                                <div class="w-8 h-8 rounded-full border-2 transition-all group-hover:scale-110 {{ request('attribute_value') == $value['id'] ? 'border-primary ring-2 ring-primary/20 scale-110' : 'border-white shadow-sm' }}"
                                                    style="background-color: {{ $value['color_code'] }}">
                                                </div>
                                            </label>
                                        @else
                                            <label class="cursor-pointer group">
                                                <input type="radio" name="attribute_value" value="{{ $value['id'] }}" 
                                                    class="hidden" 
                                                    {{ request('attribute_value') == $value['id'] ? 'checked' : '' }}
                                                    onchange="this.form.submit()">
                                                <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all {{ request('attribute_value') == $value['id'] ? 'bg-primary text-white border-primary shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-primary hover:text-primary' }}">
                                                    {{ $value['label'] }}
                                                </span>
                                            </label>
                                        @endif
                                    @endforeach
                                    @if(request('attribute_value'))
                                        <button type="button" onclick="clearFilter('attribute_value')" class="text-[10px] text-gray-400 hover:text-red-500 uppercase font-bold tracking-wider pt-1">Clear</button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @endif

                        <!-- Specifications -->
                        @if(isset($filters['specifications']))
                            @foreach($filters['specifications'] as $spec)
                            <div class="mb-8 border-t border-gray-100 pt-6">
                                <label class="block text-sm font-semibold text-dark mb-3">{{ $spec['name'] }}</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($spec['values'] as $val)
                                    <label class="flex items-center justify-between group cursor-pointer">
                                        <div class="flex items-center">
                                            <input type="radio" name="specification_value" value="{{ $val['id'] }}" 
                                                class="rounded-full border-gray-300 text-primary focus:ring-primary/20 w-4 h-4"
                                                {{ request('specification_value') == $val['id'] ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <span class="ml-3 text-sm text-gray-600 group-hover:text-primary transition-colors {{ request('specification_value') == $val['id'] ? 'text-primary font-semibold' : '' }}">{{ $val['value'] }}</span>
                                        </div>
                                        <span class="text-xs text-gray-400">({{ $val['product_count'] }})</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
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

                        <!-- Status Filters -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-dark mb-3">Availability</label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="in_stock" value="1" 
                                    class="rounded border-gray-300 text-primary focus:ring-primary/20 w-4 h-4"
                                    {{ $inStock ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="ml-3 text-sm text-gray-600">In Stock Only</span>
                            </label>
                        </div>
                        
                        <!-- Hidden fields for sorting -->
                        <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'newest' }}">
                    </form>
                </div>
            </aside>

            <!-- Product Grid Area -->
            <main class="lg:w-3/4">
                <!-- Sorting & Results Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-8 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">
                            Showing <span class="text-dark font-bold font-poppins">{{ $paginator['from'] ?? 0 }}-{{ $paginator['to'] ?? 0 }}</span> 
                            of <span class="text-dark font-bold">{{ $paginator['total'] ?? 0 }}</span> results
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-500 whitespace-nowrap">Sort by:</label>
                        <select id="sortBySelect" onchange="updateSorting(this.value)"
                            class="bg-gray-50 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 text-dark font-medium cursor-pointer">
                            <option value="newest" {{ ($sortBy ?? 'newest') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                            <option value="price_asc" {{ ($sortBy ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ ($sortBy ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ ($sortBy ?? '') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>

                @if(count($products) > 0)
                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="product-card group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                        <!-- Image -->
                        <div class="relative aspect-[4/5] overflow-hidden">
                            <a href="{{ route('customer.products.details', $product['slug']) }}" class="block h-full">
                                <img src="{{ Str::startsWith($product['main_image'] ?? '', 'http') ? $product['main_image'] : asset('storage/' . ($product['main_image'] ?? '')) }}" 
                                    alt="{{ $product['name'] }}" 
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                                    onerror="this.src='https://placehold.co/400x500?text=Craft+Fashion'">
                            </a>
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product['is_new'])
                                <span class="badge-tag new-badge">New</span>
                                @endif
                                @if($product['is_featured'])
                                <span class="badge-tag featured-badge">Featured</span>
                                @endif
                            </div>

                        </div>

                        <!-- Info -->
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="mb-auto">
                                <p class="text-[10px] uppercase tracking-widest text-primary font-bold mb-1">{{ $product['category_name'] ?? 'Boutique' }}</p>
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
                                
                                <button class="add-to-cart-btn w-10 h-10 bg-dark text-white rounded-xl hover:bg-primary transition-colors" 
                                    data-variant-id="{{ $product['default_variant_id'] }}"
                                    title="Add to Cart">
                                    <i class="fas fa-cart-plus"></i>
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
                        <a href="{{ route('customer.products.list', array_merge(request()->query(), ['page' => $paginator['current_page'] - 1])) }}" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary transition-all">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                        @endif

                        @for($i = 1; $i <= $paginator['last_page']; $i++)
                            @if($i == 1 || $i == $paginator['last_page'] || ($i >= $paginator['current_page'] - 1 && $i <= $paginator['current_page'] + 1))
                                <a href="{{ route('customer.products.list', array_merge(request()->query(), ['page' => $i])) }}" 
                                    class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all
                                    {{ $i == $paginator['current_page'] ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary' }}">
                                    {{ $i }}
                                </a>
                            @elseif($i == 2 || $i == $paginator['last_page'] - 1)
                                <span class="px-1 text-gray-300">...</span>
                            @endif
                        @endfor

                        @if($paginator['current_page'] < $paginator['last_page'])
                        <a href="{{ route('customer.products.list', array_merge(request()->query(), ['page' => $paginator['current_page'] + 1])) }}" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary transition-all">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                        @endif
                    </nav>
                </div>
                @endif

                @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-gray-300 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-2">No Products Found</h3>
                    <p class="text-gray-500 mb-8 max-w-xs mx-auto">We couldn't find any products matching your current filters.</p>
                    <a href="{{ route('customer.products.list') }}" class="inline-block px-8 py-3 bg-primary text-white rounded-full font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
                        Reset All Filters
                    </a>
                </div>
                @endif
            </main>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add to Cart Functionality
        const cartBtns = document.querySelectorAll('.add-to-cart-btn');
        cartBtns.forEach(btn => {
            btn.addEventListener('click', async function() {
                const variantId = this.getAttribute('data-variant-id');
                const originalContent = this.innerHTML;
                
                this.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
                this.disabled = true;

                try {
                    const response = await axios.post('{{ route("customer.cart.add") }}', {
                        variant_id: variantId,
                        quantity: 1
                    });

                    if(response.data.success) {
                        showToast('Added to bag!', 'success');
                        if(typeof updateCartCount === 'function') {
                            updateCartCount(response.data.cart_count);
                        }
                    } else {
                        showToast(response.data.message || 'Error occurred', 'error');
                    }
                } catch(error) {
                    console.error(error);
                    // Show specific error message from server or generic error
                    const errorMessage = error.response?.data?.message || 'Unable to add to cart';
                    showToast(errorMessage, 'error');
                } finally {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }
            });
        });


        // Update Sorting Function
        window.updateSorting = function(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort_by', value);
            url.searchParams.set('page', 1);
            window.location.href = url.href;
        };

        // Clear Filter Function
        window.clearFilter = function(name) {
            const url = new URL(window.location.href);
            url.searchParams.delete(name);
            url.searchParams.set('page', 1);
            window.location.href = url.href;
        };
    });
</script>
@endpush