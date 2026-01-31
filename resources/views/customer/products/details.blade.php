@extends('customer.layouts.master')

@section('title', ($product['meta_title'] ?? $product['name']) . ' | ' . config('constants.SITE_NAME'))
@section('description', $product['meta_description'] ?? Str::limit(strip_tags($product['description']), 160))

@if (isset($product['main_image']))
    @section('og_image', asset($product['main_image']))
@endif

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Breadcrumbs -->
        <div class="max-w-7xl mx-auto px-4 py-4">
            <nav class="flex text-sm text-gray-500">
                <a href="{{ route('customer.home.index') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('customer.products.list') }}" class="hover:text-primary transition-colors">Products</a>
                <span class="mx-2">/</span>
                @if (isset($product['category']))
                    <a href="{{ route('customer.category.products', $product['category']['slug']) }}"
                        class="hover:text-primary transition-colors">{{ $product['category']['name'] }}</a>
                    <span class="mx-2">/</span>
                @endif
                <span class="text-gray-900 font-medium truncate">{{ $product['name'] }}</span>
            </nav>
        </div>

        @if (isset($product))
            <!-- Product Section -->
            <section class="py-8">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
                        <!-- Product Images -->
                        <div class="space-y-6">
                            <div class="relative aspect-square rounded-2xl overflow-hidden bg-white shadow-lg group">
                                <img id="mainImage" src="{{ (isset($product['main_image']) && Str::startsWith($product['main_image'], 'http')) ? $product['main_image'] : asset('storage/' . ($product['main_image'] ?? 'images/placeholder-product.jpg')) }}"
                                    alt="{{ $product['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <div id="discountBadge" class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-md {{ $product['discount_percent'] > 0 ? '' : 'hidden' }}">
                                    {{ $product['discount_percent'] }}% OFF
                                </div>

                                <button id="zoomBtn" class="absolute bottom-4 right-4 bg-white/80 backdrop-blur-sm p-3 rounded-full shadow-md text-gray-700 hover:text-primary transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Thumbnail Gallery -->
                            <div id="thumbnailGallery" class="flex gap-4 overflow-x-auto pb-2 custom-scrollbar">
                                @foreach ($product['images'] as $image)
                                    <div class="thumbnail-item flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 {{ $image['is_primary'] ? 'border-primary' : 'border-gray-200' }} cursor-pointer transition-all duration-300 hover:scale-105 hover:border-primary"
                                        data-image="{{ Str::startsWith($image['url'], 'http') ? $image['url'] : asset('storage/' . $image['url']) }}"
                                        onclick="changeMainImage(this)">
                                        <img src="{{ Str::startsWith($image['url'], 'http') ? $image['url'] : asset('storage/' . $image['url']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="flex flex-col">
                            <div class="mb-6">
                                @if(isset($product['brand']))
                                    <span class="text-primary font-semibold uppercase tracking-wider text-sm">{{ $product['brand']['name'] }}</span>
                                @endif
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">{{ $product['name'] }}</h1>
                                
                                <div class="flex items-center gap-4 mb-6">
                                    <a href="#reviews" onclick="scrollToReviews(event)" class="flex items-center group cursor-pointer">
                                        <div class="flex items-center text-yellow-400 group-hover:scale-110 transition-transform duration-300">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product['rating']))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i == ceil($product['rating']) && $product['rating'] % 1 >= 0.5)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-sm ml-4 group-hover:text-primary transition-colors">({{ $product['review_count'] }} Reviews)</span>
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <span id="stockStatus" class="{{ $product['is_in_stock'] ? 'text-green-600' : 'text-red-600' }} font-medium text-sm">
                                        <i class="fas {{ $product['is_in_stock'] ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $product['is_in_stock'] ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>

                                <div class="flex items-baseline gap-4 mb-8">
                                    <span id="currentPriceDisplay" class="text-4xl font-bold text-primary">₹{{ number_format($product['price'], 0) }}</span>
                                    @if ($product['compare_price'] && $product['compare_price'] > $product['price'])
                                        <span id="comparePriceDisplay" class="text-xl text-gray-400 line-through">₹{{ number_format($product['compare_price'], 0) }}</span>
                                        <span id="discountPercentDisplay" class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-bold">
                                            Save {{ $product['discount_percent'] }}%
                                        </span>
                                    @endif
                                </div>

                                <p class="text-gray-600 leading-relaxed max-w-xl">
                                    {{ $product['short_description'] }}
                                </p>
                            </div>

                            <hr class="border-gray-200 mb-8">

                            <!-- Variants / Attributes -->
                            <div class="space-y-8 mb-8" id="productAttributes">
                                @if(isset($product['attribute_groups']) && !empty($product['attribute_groups']))
                                    @foreach($product['attribute_groups'] as $groupName => $group)
                                        <div class="variant-group">
                                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">{{ $groupName }}</h3>
                                            <div class="flex flex-wrap gap-3">
                                                @foreach($group['options'] as $option)
                                                    <button type="button" 
                                                        class="attribute-btn px-4 py-2 rounded-lg border-2 border-gray-200 text-gray-700 font-medium transition-all duration-300 hover:border-primary hover:text-primary focus:outline-none"
                                                        data-attribute-name="{{ $groupName }}"
                                                        data-attribute-value="{{ $option['value'] }}"
                                                        onclick="selectAttribute(this, '{{ $groupName }}', '{{ $option['value'] }}')">
                                                        {{ $option['label'] }}
                                                        @if($group['type'] === 'color' && isset($option['color_code']))
                                                            <span class="inline-block w-4 h-4 rounded-full ml-2 border border-gray-200" style="background-color: {{ $option['color_code'] }}"></span>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <!-- Selected Variant Info -->
                                <div id="selectedVariantInfo" class="hidden animate-fade-in">
                                    <div class="bg-primary/5 border border-primary/10 rounded-xl p-4">
                                        <p id="selectedVariantText" class="text-sm text-primary font-medium"></p>
                                        <div id="selectedVariantPrice" class="mt-2 hidden">
                                            <span class="text-xs text-gray-500">Price for selection:</span>
                                            <div class="flex items-center gap-2">
                                                <span id="variantPriceDisplay" class="text-lg font-bold text-primary"></span>
                                                <span id="variantComparePriceDisplay" class="text-sm text-gray-400 line-through hidden"></span>
                                                <span id="variantDiscountPercent" class="text-xs font-bold text-red-600 hidden"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity and Actions -->
                            <div class="space-y-6 mt-auto">
                                <div class="flex flex-wrap items-center gap-6">
                                    <div class="flex items-center border-2 border-gray-200 rounded-xl bg-white">
                                        <button onclick="decreaseQuantity()" id="decreaseQty" class="p-3 text-gray-600 hover:text-primary transition-colors disabled:opacity-30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input type="number" id="quantity" value="1" min="1" max="10" readonly class="w-12 text-center font-bold text-gray-900 border-none focus:ring-0 bg-transparent">
                                        <button onclick="increaseQuantity()" id="increaseQty" class="p-3 text-gray-600 hover:text-primary transition-colors disabled:opacity-30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-sm text-gray-500 mb-1">Total Price</p>
                                        <p class="text-2xl font-bold text-gray-900" id="totalPriceValue">₹{{ number_format($product['price'], 0) }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <button onclick="addToCart()" id="addToCartBtn" class="flex items-center justify-center gap-3 px-8 py-5 bg-gray-900 text-white rounded-2xl font-bold hover:bg-gray-800 transition-all duration-300 transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-cart-plus"></i>
                                        <span id="addToCartText">{{ $product['is_in_stock'] ? 'Add to Cart' : 'Out of Stock' }}</span>
                                    </button>
                                    <button onclick="buyNow()" id="buyNowBtn" class="flex items-center justify-center gap-3 px-8 py-5 bg-primary text-white rounded-2xl font-bold hover:bg-primary-dark transition-all duration-300 transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-bolt"></i>
                                        Buy Now
                                    </button>
                                </div>

                                <button onclick="handleWishlistClick(this)" id="wishlistBtn" data-wishlist-variant-id="{{ $product['default_variant_id'] }}" class="flex items-center justify-center gap-3 w-full py-4 text-gray-600 font-medium hover:text-red-500 transition-colors">
                                     <i class="{{ in_array($product['default_variant_id'], $wishlistVariantIds ?? []) ? 'fas text-red-500' : 'far' }} fa-heart"></i>
                                     <span class="wishlist-text">{{ in_array($product['default_variant_id'], $wishlistVariantIds ?? []) ? 'Remove from Wishlist' : 'Add to Wishlist' }}</span>
                                </button>
                            </div>

                            <!-- Trust Badges -->
                            <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4 py-8 border-t border-gray-200">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-primary mb-3">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">Fast Shipping</span>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-primary mb-3">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">Secure Payment</span>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-primary mb-3">
                                        <i class="fas fa-undo"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">Easy Returns</span>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-primary mb-3">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tabs Section -->
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="border-b border-gray-200 mb-12">
                        <div class="flex gap-12">
                            <button class="tab-button active pb-4 text-lg font-bold text-gray-900 border-b-4 border-primary transition-all">Description</button>
                            <button class="tab-button pb-4 text-lg font-medium text-gray-500 hover:text-primary transition-all">Specifications</button>
                            <button class="tab-button pb-4 text-lg font-medium text-gray-500 hover:text-primary transition-all">Reviews ({{ $product['review_count'] }})</button>
                        </div>
                    </div>

                    <div id="description" class="tab-content animate-fade-in active">
                        <div class="prose prose-lg max-w-none text-gray-600">
                            {!! $product['description'] !!}
                        </div>
                    </div>

                    <div id="specifications" class="tab-content animate-fade-in hidden">
                        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                @forelse($product['specifications'] as $spec)
                                    <div class="flex justify-between py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 px-2 transition-colors">
                                        <dt class="font-bold text-gray-900">{{ $spec['name'] }}</dt>
                                        <dd class="text-gray-600">{{ $spec['value'] }}</dd>
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic">No specifications available for this product.</p>
                                @endforelse
                                <div class="flex justify-between py-4 border-b border-gray-50 hover:bg-gray-50/50 px-2 transition-colors">
                                    <dt class="font-bold text-gray-900">SKU</dt>
                                    <dd class="text-gray-600">{{ $product['sku'] ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div id="reviews" class="tab-content animate-fade-in hidden">
                        <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-100">
                            <div class="flex flex-col md:flex-row items-center gap-12 mb-12">
                                <div class="text-center md:text-left">
                                    <div class="text-6xl font-bold text-gray-900 mb-2">{{ number_format($product['rating'], 1) }}</div>
                                    <div class="flex items-center text-yellow-400 text-2xl mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product['rating']))
                                                <i class="fas fa-star"></i>
                                            @elseif($i == ceil($product['rating']) && $product['rating'] % 1 >= 0.5)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-gray-500 font-medium">Based on {{ $product['review_count'] }} Reviews</p>
                                </div>
                                <div class="flex-grow w-full max-w-md space-y-3">
                                    @foreach([5, 4, 3, 2, 1] as $star)
                                        <div class="flex items-center gap-4">
                                            <span class="w-12 text-sm font-bold text-gray-600 whitespace-nowrap">{{ $star }} Star</span>
                                            <div class="flex-grow h-3 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-yellow-400 transition-all duration-1000" style="width: {{ $ratingBreakdown[$star]['percent'] }}%"></div>
                                            </div>
                                            <span class="w-12 text-sm text-gray-400">{{ $ratingBreakdown[$star]['percent'] }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Write a Review Button -->
                            <div class="flex flex-col sm:flex-row justify-between items-center mb-12 py-6 border-y border-gray-50 gap-6">
                                <h3 class="text-2xl font-bold text-gray-900">Customer Reviews</h3>
                                <button onclick="toggleReviewForm()" class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-2xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                                    <i class="fas fa-pen"></i> Write a Review
                                </button>
                            </div>

                            <!-- Review Form (Hidden by default) -->
                            <div id="reviewFormContent" class="hidden mb-16 bg-gray-50 rounded-[2.5rem] p-8 md:p-12 border border-primary/10 animate-fade-in">
                                <h4 class="text-2xl font-bold text-gray-900 mb-8">Share Your Thoughts</h4>
                                <form action="{{ route('customer.products.review.store', $product['slug']) }}" method="POST" class="space-y-8">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-3">
                                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-widest">Your Name</label>
                                            <input type="text" name="user_name" required class="w-full px-6 py-4 rounded-2xl border-gray-200 focus:border-primary focus:ring-primary transition-all bg-white shadow-sm" placeholder="Enter your full name">
                                        </div>
                                        <div class="space-y-3">
                                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-widest">Your Rating</label>
                                            <div class="flex items-center gap-3 text-3xl text-gray-200" id="starRatingInput">
                                                @for($i=1; $i<=5; $i++)
                                                    <i class="fas fa-star cursor-pointer hover:text-yellow-400 transition-colors" data-rating="{{ $i }}" onclick="setRating({{ $i }})"></i>
                                                @endfor
                                                <input type="hidden" name="rating" id="ratingInput" value="5" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <label class="block text-sm font-bold text-gray-700 uppercase tracking-widest">Your Review</label>
                                        <textarea name="review" rows="5" required class="w-full px-6 py-4 rounded-2xl border-gray-200 focus:border-primary focus:ring-primary transition-all bg-white shadow-sm" placeholder="What did you like or dislike about this product?"></textarea>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-end gap-4">
                                        <button type="button" onclick="toggleReviewForm()" class="px-8 py-4 text-gray-500 font-bold hover:text-gray-900 transition-all">Cancel</button>
                                        <button type="submit" class="px-12 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-gray-800 transition-all shadow-xl">Submit Review</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Review List -->
                            <div class="space-y-12">
                                @forelse($reviews ?? [] as $review)
                                    <div class="bg-gray-50/50 rounded-[2rem] p-8 border border-gray-100 transition-all hover:bg-white hover:shadow-xl group">
                                        <div class="flex flex-col md:flex-row md:items-start gap-8">
                                            <div class="flex-shrink-0">
                                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary font-bold text-xl shadow-sm border border-gray-100 uppercase">
                                                    {{ substr($review->user_name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex-grow">
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 text-xl">{{ $review->user_name }}</h4>
                                                        <div class="flex items-center text-yellow-400 text-sm mt-1">
                                                            @for($i=1; $i<=5; $i++) 
                                                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i> 
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-gray-400">
                                                        <i class="far fa-clock text-xs"></i>
                                                        <span class="text-sm font-medium">{{ $review->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                <p class="text-gray-600 leading-relaxed text-lg italic">"{{ $review->review }}"</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-24 bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-gray-200 mx-auto mb-8 shadow-sm">
                                            <i class="fas fa-comment-dots text-5xl"></i>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-3">No Reviews Yet</h3>
                                        <p class="text-gray-500 mb-10 max-w-xs mx-auto text-lg leading-relaxed text-balance">Be the first to share your experience with this exquisite collection.</p>
                                        <button onclick="toggleReviewForm()" class="px-10 py-5 bg-primary text-white rounded-2xl font-bold hover:bg-primary-dark transition-all transform hover:scale-105 shadow-2xl shadow-primary/20">Write the First Review</button>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Related Products Section -->
            @if (isset($relatedProducts) && count($relatedProducts) > 0)
                <section class="py-24 bg-gray-50">
                    <div class="max-w-7xl mx-auto px-4">
                        <div class="flex items-end justify-between mb-12">
                            <div>
                                <h1 class="text-primary font-bold uppercase tracking-widest text-sm mb-3">You might like</h1>
                                <h2 class="text-4xl font-bold text-gray-900">Related Products</h2>
                            </div>
                            <a href="{{ route('customer.products.list') }}" class="group flex items-center gap-2 text-primary font-bold hover:text-primary-dark transition-colors">
                                View All
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            @foreach ($relatedProducts as $relatedProduct)
                                <div class="group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                                    <a href="{{ route('customer.products.details', $relatedProduct['slug']) }}" class="block relative aspect-[4/5] overflow-hidden">
                                        <img src="{{ Str::startsWith($relatedProduct['main_image'] ?? '', 'http') ? $relatedProduct['main_image'] : asset('storage/' . ($relatedProduct['main_image'] ?? '')) }}" 
                                            alt="{{ $relatedProduct['name'] }}"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                        @if($relatedProduct['discount_percent'] > 0)
                                            <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                                -{{ $relatedProduct['discount_percent'] }}%
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                    </a>
                                    <div class="p-6">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ $relatedProduct['category'] ?? 'Product' }}</p>
                                        <h3 class="font-bold text-gray-900 mb-3 line-clamp-2 hover:text-primary transition-colors text-lg">
                                            <a href="{{ route('customer.products.details', $relatedProduct['slug']) }}">{{ $relatedProduct['name'] }}</a>
                                        </h3>
                                        <div class="flex items-center justify-between mt-auto">
                                            <div class="flex flex-col">
                                                <span class="text-2xl font-black text-primary">₹{{ number_format($relatedProduct['price'], 0) }}</span>
                                                @if($relatedProduct['compare_price'] > $relatedProduct['price'])
                                                    <span class="text-sm text-gray-400 line-through font-medium">₹{{ number_format($relatedProduct['compare_price'], 0) }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button onclick="quickAddToCart('{{ $relatedProduct['id'] }}', '{{ $relatedProduct['default_variant_id'] }}')" class="w-11 h-11 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-primary transition-all duration-300 shadow-lg transform active:scale-95">
                                                    <i class="fas fa-cart-plus text-sm"></i>
                                                </button>
                                                 <button onclick="addToWishlist('{{ $relatedProduct['default_variant_id'] }}', this)" data-wishlist-variant-id="{{ $relatedProduct['default_variant_id'] }}" class="w-11 h-11 bg-white text-gray-900 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all duration-300 shadow-md transform active:scale-95">
                                                    <i class="{{ in_array($relatedProduct['default_variant_id'], $wishlistVariantIds ?? []) ? 'fas text-red-500' : 'far' }} fa-heart text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endif
    </div>
@endsection

@push('scripts')

<script>
    // Show session messages if any
    if (typeof showToast === 'function') {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    }

    // Product data from PHP
    const productData = @json($product);
    const availableVariants = @json($product['variants'] ?? []);
    const attributeGroups = @json($product['attribute_groups'] ?? []);
    const productImages = @json($product['images'] ?? []);

    // State
    let selectedAttributes = {};
    let selectedVariant = null;
    let currentPrice = {{ $product['price'] ?? 0 }};
    let currentQuantity = 1;
    let maxQuantity = {{ $product['stock_quantity'] ?? 10 }};

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variant selection
        initializeVariantSelection();
        
        // Tab switching
        setupTabs();
        
        // Setup initial UI
        updateTotalPrice();
        updateQuantityButtons();

        // Configure Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    });

    function initializeVariantSelection() {
        if (availableVariants.length > 0) {
            // Find default variant
            const defaultVariant = availableVariants.find(v => v.is_default) || availableVariants[0];
            const totalGroups = Object.keys(attributeGroups).length;

            if (totalGroups > 0 && defaultVariant.attributes && defaultVariant.attributes.length > 0) {
                defaultVariant.attributes.forEach(attr => {
                    const btn = document.querySelector(`[data-attribute-name="${attr.attribute_name}"][data-attribute-value="${attr.value}"]`);
                    if (btn) selectAttribute(btn, attr.attribute_name, attr.value, true);
                });
            } else {
                // Simple product or no attributes to select
                selectedVariant = defaultVariant;
                updateVariantUI(defaultVariant);
                updateAddToCartButton();
            }
        }
    }

    function selectAttribute(button, name, value, isInitial = false) {
        // Update selected state
        selectedAttributes[name] = value;
        
        // Update UI buttons in the group
        const group = button.closest('.variant-group');
        group.querySelectorAll('.attribute-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary', 'bg-primary/5');
            btn.classList.add('border-gray-200', 'text-gray-700');
        });
        button.classList.remove('border-gray-200', 'text-gray-700');
        button.classList.add('border-primary', 'text-primary', 'bg-primary/5');

        // Find matching variant
        const matchedVariant = findMatchingVariant();
        if (matchedVariant) {
            selectedVariant = matchedVariant;
            updateVariantUI(matchedVariant);
        } else {
            // Partial selection or no match
            updatePartialSelectionUI();
        }
        
        updateAddToCartButton();
    }

    function findMatchingVariant() {
        if (!availableVariants.length) return null;
        
        const totalGroups = Object.keys(attributeGroups).length;
        if (Object.keys(selectedAttributes).length < totalGroups) return null;

        return availableVariants.find(variant => {
            if (!variant.attributes) return false;
            return variant.attributes.every(attr => selectedAttributes[attr.attribute_name] === attr.value);
        });
    }

    function updateVariantUI(variant) {
        currentPrice = variant.price;
        maxQuantity = Math.min(variant.stock_quantity || 10, 10);
        
        // Update price display
        document.getElementById('currentPriceDisplay').textContent = formatPrice(variant.price);
        const comparePrice = document.getElementById('comparePriceDisplay');
        if (variant.compare_price && variant.compare_price > variant.price) {
            comparePrice.textContent = formatPrice(variant.compare_price);
            comparePrice.classList.remove('hidden');
        } else if (comparePrice) {
            comparePrice.classList.add('hidden');
        }

        // Update variant info box
        const info = document.getElementById('selectedVariantInfo');
        const text = document.getElementById('selectedVariantText');
        const selectedEntries = Object.entries(selectedAttributes);
        
        if (selectedEntries.length > 0) {
            info.classList.remove('hidden');
            text.textContent = 'Selected: ' + selectedEntries.map(([k, v]) => `${k}: ${v}`).join(', ');
        } else {
            info.classList.add('hidden');
        }

        // Update images if variant has specific images
        if (variant.images && variant.images.length > 0) {
            updateThumbnailGallery(variant.images);
        }

        // Update stock status
        const status = document.getElementById('stockStatus');
        const isInStock = variant.stock_quantity > 0;
        status.className = `${isInStock ? 'text-green-600' : 'text-red-600'} font-medium text-sm`;
        status.innerHTML = `<i class="fas ${isInStock ? 'fa-check-circle' : 'fa-times-circle'} mr-1"></i>${isInStock ? 'In Stock' : 'Out of Stock'}`;

        updateTotalPrice();
    }

    function updatePartialSelectionUI() {
        document.getElementById('selectedVariantInfo').classList.add('hidden');
    }

    function updateThumbnailGallery(images) {
        const gallery = document.getElementById('thumbnailGallery');
        const storageBase = "{{ asset('storage') }}/";
        gallery.innerHTML = '';
        images.forEach((img, idx) => {
            const div = document.createElement('div');
            const imageUrl = img.url.startsWith('http') ? img.url : storageBase + img.url;
            div.className = `thumbnail-item flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 ${idx === 0 ? 'border-primary' : 'border-gray-200'} cursor-pointer transition-all duration-300 hover:scale-105 hover:border-primary`;
            div.dataset.image = imageUrl;
            div.onclick = function() { changeMainImage(this); };
            div.innerHTML = `<img src="${imageUrl}" class="w-full h-full object-cover">`;
            gallery.appendChild(div);
        });
        if (images.length > 0) {
            const firstImageUrl = images[0].url.startsWith('http') ? images[0].url : storageBase + images[0].url;
            document.getElementById('mainImage').src = firstImageUrl;
        }
    }

    function changeMainImage(element) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = element.dataset.image;
        
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('border-primary');
            item.classList.add('border-gray-200');
        });
        element.classList.remove('border-gray-200');
        element.classList.add('border-primary');
    }

    function updateAddToCartButton() {
        const btn = document.getElementById('addToCartBtn');
        const text = document.getElementById('addToCartText');
        const totalGroups = Object.keys(attributeGroups).length;
        
        if (totalGroups > 0 && Object.keys(selectedAttributes).length < totalGroups) {
            text.textContent = 'Select Options';
            btn.disabled = true;
        } else if (selectedVariant && selectedVariant.stock_quantity <= 0) {
            text.textContent = 'Out of Stock';
            btn.disabled = true;
        } else {
            text.textContent = 'Add to Cart';
            btn.disabled = false;
        }
    }

    // Tabs
    function setupTabs() {
        const buttons = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        buttons.forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => {
                    b.classList.remove('active', 'text-primary', 'border-primary', 'border-b-4');
                    b.classList.add('text-gray-500', 'font-medium');
                });
                btn.classList.add('active', 'text-primary', 'border-primary', 'border-b-4');
                btn.classList.remove('text-gray-500', 'font-medium');

                contents.forEach(c => c.classList.add('hidden'));
                contents[idx].classList.remove('hidden');
            });
        });
    }

    // Pricing & Quantity helpers
    function formatPrice(price) {
        return '₹' + new Intl.NumberFormat('en-IN').format(Math.round(price));
    }

    function updateTotalPrice() {
        const display = document.getElementById('totalPriceValue');
        display.textContent = formatPrice(currentPrice * currentQuantity);
    }

    function decreaseQuantity() {
        if (currentQuantity > 1) {
            currentQuantity--;
            document.getElementById('quantity').value = currentQuantity;
            updateTotalPrice();
            updateQuantityButtons();
        }
    }

    function increaseQuantity() {
        if (currentQuantity < maxQuantity) {
            currentQuantity++;
            document.getElementById('quantity').value = currentQuantity;
            updateTotalPrice();
            updateQuantityButtons();
        }
    }

    function updateQuantityButtons() {
        document.getElementById('decreaseQty').disabled = currentQuantity <= 1;
        document.getElementById('increaseQty').disabled = currentQuantity >= maxQuantity;
    }

    // API Actions
    async function addToCart() {
        if (!selectedVariant) return;
        
        const btn = document.getElementById('addToCartBtn');
        const text = document.getElementById('addToCartText');
        const originalText = text.textContent;
        
        btn.disabled = true;
        text.textContent = 'Adding...';

        try {
            const response = await axios.post('{{ route('customer.cart.add') }}', {
                variant_id: selectedVariant.id,
                quantity: currentQuantity
            });

            if (response.data.success) {
                showToast('Added to cart successfully!', 'success');
                // Update cart count in header if possible
                if (typeof updateCartCount === 'function') {
                    updateCartCount(response.data.cart_count);
                }
            } else {
                showToast(response.data.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            showToast('Error adding to cart', 'error');
        } finally {
            text.textContent = originalText;
            btn.disabled = false;
        }
    }

    async function buyNow() {
        if (!selectedVariant) {
            showToast('Please select all product options', 'error');
            return;
        }
        
        const btn = document.getElementById('buyNowBtn');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

        try {
            const response = await axios.post('{{ route('customer.cart.add') }}', {
                variant_id: selectedVariant.id,
                quantity: currentQuantity
            });

            if (response.data.success) {
                if (typeof updateCartCount === 'function') {
                    updateCartCount(response.data.cart_count);
                }
                window.location.href = '{{ route('customer.cart') }}';
            } else {
                showToast(response.data.message || 'Failed to process', 'error');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        } catch (error) {
            console.error(error);
            showToast('Error processing request', 'error');
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    }

    function handleWishlistClick(btn) {
        const variantId = selectedVariant ? selectedVariant.id : (typeof productData !== 'undefined' ? productData.default_variant_id : null);
        addToWishlist(variantId, btn);
    }

    async function quickAddToCart(productId, variantId) {
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
            }
        } catch (error) {
            showToast('Failed to add to cart', 'error');
        }
    }

    // Review Actions
    function scrollToReviews(e) {
        e.preventDefault();
        const reviewsTab = document.querySelectorAll('.tab-button')[2]; 
        if (reviewsTab) {
            reviewsTab.click();
            document.getElementById('reviews').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function toggleReviewForm() {
        const form = document.getElementById('reviewFormContent');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('#starRatingInput i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-yellow-400');
            } else {
                star.classList.remove('fas', 'text-yellow-400');
                star.classList.add('far');
            }
        });
    }

    // Initialize stars on load
    setRating(5);
</script>
@endpush