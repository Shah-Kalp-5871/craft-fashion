@extends('customer.layouts.master')

@section('title', $product['name'] . ' - APIQO Jewellery')
@section('meta_description',
    $product['meta_description'] ??
    ($product['short_description'] ??
    'Product details and
    specifications'))
@section('og_title', $product['name'] . ' - APIQO Jewellery')
@section('og_description',
    $product['meta_description'] ??
    ($product['short_description'] ??
    'View detailed information
    about this product'))
    @if (isset($product['main_image']))
        @section('og_image', $product['main_image'])
    @endif

    @section('styles')
        <style>
            /* Animation Keyframes */
            @keyframes fade-in {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slide-left {
                from {
                    opacity: 0;
                    transform: translateX(-50px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slide-right {
                from {
                    opacity: 0;
                    transform: translateX(50px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes scale-in {
                from {
                    opacity: 0;
                    transform: scale(0.9);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            @keyframes heart-beat {
                0% {
                    transform: scale(1);
                }

                14% {
                    transform: scale(1.3);
                }

                28% {
                    transform: scale(1);
                }

                42% {
                    transform: scale(1.3);
                }

                70% {
                    transform: scale(1);
                }
            }

            @keyframes pulse-once {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.05);
                    opacity: 0.8;
                }
            }

            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fadeOut {
                from {
                    opacity: 1;
                }

                to {
                    opacity: 0;
                }
            }

            /* Animation Classes */
            .animate-fade-in {
                animation: fade-in 0.6s ease-out both;
            }

            .animate-slide-left {
                animation: slide-left 0.6s ease-out both;
            }

            .animate-slide-right {
                animation: slide-right 0.6s ease-out both;
            }

            .animate-scale-in {
                animation: scale-in 0.6s ease-out both;
            }

            .animate-pulse-once {
                animation: pulse-once 2s ease-in-out;
            }

            .heart-beat {
                animation: heart-beat 1s ease-in-out;
            }

            /* Product hover effects */
            .product-card:hover img {
                transform: scale(1.1);
            }

            .product-card {
                transition: all 0.3s ease;
            }

            .product-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(139, 69, 19, 0.1);
            }

            /* Custom scrollbar for tabs */
            .tab-scroll {
                scrollbar-width: thin;
                scrollbar-color: #d97706 #f1f1f1;
            }

            .tab-scroll::-webkit-scrollbar {
                height: 4px;
            }

            .tab-scroll::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 2px;
            }

            .tab-scroll::-webkit-scrollbar-thumb {
                background: #d97706;
                border-radius: 2px;
            }

            /* Image zoom */
            .zoomable-image {
                cursor: zoom-in;
                transition: transform 0.3s ease;
            }

            .zoomable-image.zoomed {
                cursor: zoom-out;
                transform: scale(2);
                z-index: 1000;
                position: relative;
            }

            /* Attribute buttons */
            .attribute-btn.active {
                background-color: #d97706 !important;
                color: white !important;
                border-color: #d97706 !important;
            }

            .attribute-btn.disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* Skeleton loading */
            .skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
            }

            @keyframes loading {
                0% {
                    background-position: 200% 0;
                }

                100% {
                    background-position: -200% 0;
                }
            }

            /* Rating stars */
            .star-rating {
                display: inline-flex;
                direction: ltr;
            }

            .star-rating .star {
                color: #e5e7eb;
                font-size: 1.125rem;
                margin-right: 0.125rem;
            }

            .star-rating .star.active {
                color: #fbbf24;
            }

            .star-rating .star.half {
                position: relative;
            }

            .star-rating .star.half::before {
                content: '★';
                position: absolute;
                left: 0;
                width: 50%;
                overflow: hidden;
                color: #fbbf24;
            }

            /* Tab Content */
            .tab-content {
                display: block;
            }

            .tab-content.hidden {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            /* Notification */
            .custom-notification {
                animation: slideInRight 0.3s ease-out;
            }

            .custom-notification.fade-out {
                animation: fadeOut 0.5s ease-out forwards;
            }

            /* Quantity input */
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Variant loading indicator */
            .variant-loading {
                opacity: 0.7;
                pointer-events: none;
            }
        </style>
    @endsection

    @section('content')
        @if (isset($product) && $product)
            <!-- Product Detail Section -->
            <section class="relative bg-gradient-to-b from-amber-50 to-beige-100 py-12 overflow-hidden">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-200/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-amber-300/5 rounded-full blur-3xl"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 relative z-10">
                    <!-- Breadcrumb -->
                    <div class="mb-8 animate-fade-in">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('customer.home.index') }}"
                                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-home mr-2"></i>
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <a href="{{ route('customer.products.list') }}"
                                            class="ml-1 text-sm font-medium text-gray-700 hover:text-amber-600 md:ml-2 transition-colors">
                                            Products
                                        </a>
                                    </div>
                                </li>
                                @if (isset($product['category']))
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                            <a href="{{ route('customer.category.products', $product['category']['slug']) }}"
                                                class="ml-1 text-sm font-medium text-gray-700 hover:text-amber-600 md:ml-2 transition-colors">
                                                {{ $product['category']['name'] }}
                                            </a>
                                        </div>
                                    </li>
                                @endif
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                                            {{ $product['name'] }}
                                        </span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Product Detail Grid -->
                    <div id="productDetailGrid">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Product Images -->
                            <div class="animate-slide-left">
                                <div id="imageContainer">
                                    <!-- Main image will be updated dynamically -->
                                    <img id="mainImage"
                                        src="{{ $product['main_image'] ? asset('storage/' . $product['main_image']) : asset('images/placeholder-product.jpg') }}"
                                        alt="{{ $product['name'] }}"
                                        class="w-full h-auto object-cover rounded-2xl transition-transform duration-700 hover:scale-105 cursor-pointer zoomable-image"
                                        onclick="toggleZoom(this)"
                                        onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                </div>

                                <!-- Thumbnail gallery will be updated dynamically -->
                                <div id="thumbnailGallery" class="grid grid-cols-4 gap-4 mt-4">
                                    <!-- Thumbnails will be loaded here -->
                                </div>
                            </div>

                            <!-- Product Information -->
                            <div class="animate-slide-right">
                                <!-- Tags and Badges -->
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @if (isset($product['category']))
                                        <span class="bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full font-medium">
                                            {{ $product['category']['name'] }}
                                        </span>
                                    @endif

                                    @if (isset($product['brand']) && $product['brand']['name'])
                                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">
                                            {{ $product['brand']['name'] }}
                                        </span>
                                    @endif

                                    @php
                                        $hasDiscount = isset($product['compare_price']) && $product['compare_price'] > $product['price'];
                                        $discountPercent = $hasDiscount ? round((($product['compare_price'] - $product['price']) / $product['compare_price']) * 100) : 0;
                                    @endphp
                                    <span id="discountBadge"
                                        class="{{ $hasDiscount ? '' : 'hidden' }} bg-gradient-to-r from-amber-600 to-amber-800 text-white text-xs px-3 py-1 rounded-full font-bold">
                                        {{ $hasDiscount ? $discountPercent . '% OFF' : '' }}
                                    </span>

                                    @if ($product['is_featured'])
                                        <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full font-medium">
                                            Featured
                                        </span>
                                    @endif

                                    @if ($product['is_new'])
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-medium">
                                            New
                                        </span>
                                    @endif
                                </div>

                                <!-- Product Name -->
                                <h1 class="brand-title text-3xl md:text-4xl text-gray-800 mb-4">
                                    {{ $product['name'] }}
                                </h1>

                                <!-- Rating -->
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center">
                                        <div class="star-rating mr-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product['rating']))
                                                    <span class="star active">★</span>
                                                @elseif($i == ceil($product['rating']) && $product['rating'] % 1 >= 0.5)
                                                    <span class="star half">★</span>
                                                @else
                                                    <span class="star">★</span>
                                                @endif
                                            @endfor
                                        </div>
                                        <span
                                            class="font-medium text-gray-700">{{ number_format($product['rating'], 1) }}/5</span>
                                    </div>
                                    <span class="text-gray-500">•</span>
                                    <span class="text-gray-600">{{ $product['review_count'] }}
                                        Review{{ $product['review_count'] != 1 ? 's' : '' }}</span>
                                    <span class="text-gray-500">•</span>
                                    <span id="stockStatus"
                                        class="{{ $product['is_in_stock'] ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        <i
                                            class="fas {{ $product['is_in_stock'] ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $product['is_in_stock'] ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>

                                <!-- Pricing -->
                                <div class="mb-8">
                                    <div class="flex items-center gap-4 mb-2">
                                        <span class="text-4xl font-bold text-gray-900" id="currentPriceDisplay">
                                            ₹{{ number_format($product['price'], 0) }}
                                        </span>
                                        <span id="comparePriceDisplay" class="{{ $hasDiscount ? '' : 'hidden' }} text-xl text-gray-400 line-through">
                                            {{ $hasDiscount ? '₹' . number_format($product['compare_price'], 0) : '' }}
                                        </span>
                                        <span id="discountPercentDisplay" class="{{ $hasDiscount ? '' : 'hidden' }} text-lg font-bold text-green-600">
                                            {{ $hasDiscount ? 'Save ' . $discountPercent . '%' : '' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Inclusive of all taxes • Free Shipping
                                    </p>
                                </div>

                                <!-- Variant Selection -->
                                @if (isset($product['attribute_groups']) && count($product['attribute_groups']) > 0)
                                    <div class="mb-8">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Select Options</h3>
                                        <div id="variantOptions" class="space-y-6">
                                            @foreach ($product['attribute_groups'] as $attributeName => $attributeGroup)
                                                <div class="variant-group">
                                                    <h4 class="font-medium text-gray-700 mb-3">{{ $attributeName }}:</h4>
                                                    <div class="flex flex-wrap gap-3">
                                                        @foreach ($attributeGroup['options'] as $option)
                                                            <button type="button"
                                                                class="attribute-btn px-4 py-2 border border-gray-300 rounded-lg hover:border-amber-500 hover:bg-amber-50 hover:text-amber-700 transition-colors flex flex-col items-center justify-center min-w-[100px] bg-white text-gray-700"
                                                                data-attribute-name="{{ $attributeName }}"
                                                                data-attribute-value="{{ $option['value'] }}"
                                                                data-option-id="{{ $option['id'] ?? '' }}"
                                                                onclick="selectAttribute(this, '{{ $attributeName }}', '{{ $option['value'] }}')">

                                                                @if (!empty($option['color_code']))
                                                                    <div class="w-8 h-8 rounded-full mb-2 border border-gray-300"
                                                                        style="background-color: {{ $option['color_code'] }}">
                                                                    </div>
                                                                @endif

                                                                <span class="font-medium">{{ $option['label'] }}</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Selected Variant Info -->
                                    <div id="selectedVariantInfo" class="hidden mb-6">
                                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                                <div>
                                                    <p class="font-medium text-green-800" id="selectedVariantText"></p>
                                                    <p class="text-sm text-green-600">Variant selected. Ready to add to cart!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selected Variant Price Display -->
                                    <div id="selectedVariantPrice" class="hidden mb-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-3">Selected Variant Price</h3>
                                        <div class="flex items-center gap-4">
                                            <span class="text-3xl font-bold text-gray-900" id="variantPriceDisplay">
                                                ₹{{ number_format($product['price'], 0) }}
                                            </span>
                                            <span class="hidden text-xl text-gray-400 line-through"
                                                id="variantComparePriceDisplay">
                                                <!-- Will be updated dynamically -->
                                            </span>
                                            <span id="variantDiscountPercent" class="hidden text-lg font-bold text-green-600">
                                                <!-- Will be updated dynamically -->
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Product Highlights -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Product Highlights</h3>
                                    <ul class="space-y-2">
                                        @if ($product['is_featured'])
                                            <li class="flex items-center gap-2">
                                                <i class="fas fa-star text-amber-500"></i>
                                                <span>Featured product from our premium collection</span>
                                            </li>
                                        @endif
                                        @if ($product['is_new'])
                                            <li class="flex items-center gap-2">
                                                <i class="fas fa-bolt text-green-500"></i>
                                                <span>New arrival - Latest design</span>
                                            </li>
                                        @endif
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-check-circle text-blue-500"></i>
                                            <span>Premium quality materials</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-shipping-fast text-purple-500"></i>
                                            <span>Free shipping on orders above ₹999</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-undo-alt text-green-500"></i>
                                            <span>30-day return policy</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Quantity Selector -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quantity</h3>
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex items-center border border-gray-300 rounded-full hover:border-amber-500 transition-colors w-32">
                                            <button id="decreaseQty"
                                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-l-full transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                                onclick="decreaseQuantity()">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <span id="quantityDisplay"
                                                class="w-12 text-center font-semibold border-0 bg-transparent">1</span>

                                            <button id="increaseQty"
                                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-r-full transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                                onclick="increaseQuantity()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <p class="text-sm text-gray-600">Total: <span id="totalPrice"
                                                class="font-bold text-amber-700">
                                                <span id="totalPriceValue">{{ number_format($product['price'], 0) }}</span>
                                            </span></p>
                                    </div>
                                    <!-- Hidden input for actual quantity value -->
                                    <input type="hidden" id="quantity" value="1" min="1" max="10">
                                </div>

                                <!-- Stock Status -->
                                <!-- <div class="mb-8">
                                    <div id="dynamicStockStatus"
                                        class="flex items-center gap-3 {{ $product['is_in_stock'] ? 'text-green-600' : 'text-red-600' }}">
                                        <i
                                            class="fas {{ $product['is_in_stock'] ? 'fa-check-circle' : 'fa-times-circle' }} text-xl"></i>
                                        <div>
                                            <p class="font-medium">
                                                {{ $product['is_in_stock'] ? 'In Stock' : 'Out of Stock' }}
                                                @if ($product['is_in_stock'] && $product['stock_quantity'] > 0)
                                                    ({{ $product['stock_quantity'] }} available)
                                                @endif
                                            </p>
                                            @if ($product['is_in_stock'])
                                                <p class="text-sm">Order within next 2 hours for same day dispatch</p>
                                            @else
                                                <p class="text-sm">Expected restock in 7-10 days</p>
                                            @endif
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                                    <button type="button" id="addToCartBtn"
                                        class="w-full bg-gray-900 text-white py-4 rounded-full font-bold hover:bg-gray-800 transition-all duration-300 flex items-center justify-center gap-3 hover:scale-105 hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                        onclick="addToCart()" {{ !$product['is_in_stock'] ? 'disabled' : '' }}>
                                        <i class="fas fa-shopping-cart"></i>
                                        <span id="addToCartText">Add to Cart</span>
                                    </button>

                                    <button type="button" id="wishlistBtn"
                                        class="w-full bg-white border-2 border-amber-600 text-amber-600 py-4 rounded-full font-bold hover:scale-105 transition-all duration-300 flex items-center justify-center gap-3 hover:shadow-xl"
                                        onclick="toggleWishlist()">
                                        <i class="fas fa-heart"></i>
                                        <span id="wishlistText">Add to Wishlist</span>
                                    </button>

                                    <button type="button" id="buyNowBtn"
                                        class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-4 rounded-full font-bold hover:from-amber-700 hover:to-amber-900 transition-all duration-300 flex items-center justify-center gap-3 hover:scale-105 hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                        onclick="buyNow()" {{ !$product['is_in_stock'] ? 'disabled' : '' }}>
                                        <i class="fas fa-bolt"></i>
                                        Buy Now
                                    </button>
                                </div>

                                <!-- Delivery Info -->
                                <div class="bg-amber-50 rounded-2xl p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Delivery & Services</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-shipping-fast text-amber-600"></i>
                                            <div>
                                                <p class="font-medium">Free Delivery</p>
                                                <p class="text-sm text-gray-600">Order above ₹999</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-gem text-amber-600"></i>
                                            <div>
                                                <p class="font-medium">Premium Materials</p>
                                                <p class="text-sm text-gray-600">Selected for durability and finish</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-shield-alt text-amber-600"></i>
                                            <div>
                                                <p class="font-medium">Quality Checked</p>
                                                <p class="text-sm text-gray-600">Reviewed before dispatch</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-box-open text-amber-600"></i>
                                            <div>
                                                <p class="font-medium">Careful Packaging</p>
                                                <p class="text-sm text-gray-600">Packed securely for safe delivery</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Share Product -->
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span>Share:</span>
                                    <div class="flex gap-2">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                            target="_blank"
                                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text=Check%20out%20this%20product"
                                            target="_blank"
                                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-400 flex items-center justify-center hover:bg-blue-400 hover:text-white transition-colors">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a href="https://wa.me/?text=Check%20out%20this%20product%3A%20{{ url()->current() }}"
                                            target="_blank"
                                            class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white transition-colors">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="https://pinterest.com/pin/create/button/?url={{ url()->current() }}&media={{ $product['main_image'] ? asset('storage/' . $product['main_image']) : '' }}&description={{ $product['name'] }}"
                                            target="_blank"
                                            class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors">
                                            <i class="fab fa-pinterest-p"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Product Description Section -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-8">
                        <nav class="flex space-x-8 overflow-x-auto tab-scroll">
                            <button
                                class="tab-button active py-4 font-bold text-gray-800 border-b-2 border-amber-600 hover:text-amber-700 transition-colors whitespace-nowrap"
                                onclick="switchTab('description')">
                                Product Details
                            </button>
                            <button
                                class="tab-button py-4 font-medium text-gray-600 hover:text-gray-800 transition-colors whitespace-nowrap"
                                onclick="switchTab('specifications')">
                                Specifications
                            </button>
                            <button
                                class="tab-button py-4 font-medium text-gray-600 hover:text-gray-800 transition-colors whitespace-nowrap"
                                onclick="switchTab('reviews')">
                                Reviews
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tab-content active" id="description">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Description</h3>
                                <div class="prose max-w-none">
                                    @if ($product['description'])
                                        <div class="text-gray-600 mb-6 whitespace-pre-line">{{ $product['description'] }}
                                        </div>
                                    @endif
                                    @if ($product['short_description'])
                                        <div class="text-gray-600 mb-6">{{ $product['short_description'] }}</div>
                                    @endif
                                    @if (!$product['description'] && !$product['short_description'])
                                        <p class="text-gray-600">No description available for this product.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-amber-50 rounded-2xl p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Key Features</h3>
                                <div class="space-y-6">
                                    @if ($product['is_featured'])
                                        <div class="flex items-start gap-4">
                                            <i class="fas fa-star text-amber-500 mt-1"></i>
                                            <div>
                                                <h4 class="font-medium text-gray-700 mb-1">Featured Product</h4>
                                                <p class="text-sm text-gray-600">Premium selection from our collection</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($product['brand']))
                                        <div class="flex items-start gap-4">
                                            <i class="fas fa-copyright text-amber-500 mt-1"></i>
                                            <div>
                                                <h4 class="font-medium text-gray-700 mb-1">Brand</h4>
                                                <p class="text-sm text-gray-600">{{ $product['brand']['name'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex items-start gap-4">
                                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-1">Quality Assured</h4>
                                            <p class="text-sm text-gray-600">Premium materials and craftsmanship</p>
                                        </div>
                                    </div>
                                    @if (isset($product['specifications']))
                                        @foreach (array_slice($product['specifications'], 0, 3) as $spec)
                                            <div class="flex items-start gap-4">
                                                <i class="fas fa-check text-blue-500 mt-1"></i>
                                                <div>
                                                    <h4 class="font-medium text-gray-700 mb-1">{{ $spec['name'] }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $spec['value'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content hidden" id="specifications">
                        @if (isset($product['specifications']) && count($product['specifications']) > 0)
                            <div class="bg-white rounded-2xl p-8 shadow-lg">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Specifications</h3>
                                <div class="space-y-6">
                                    @foreach ($product['specifications'] as $spec)
                                        <div class="flex justify-between py-3 border-b border-gray-100">
                                            <span class="text-gray-600">{{ $spec['name'] }}</span>
                                            <span class="font-medium text-right">{{ $spec['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <p class="text-gray-600">No specifications available for this product.</p>
                            </div>
                        @endif
                    </div>

                    <div class="tab-content hidden" id="reviews">
                        <div class="bg-white rounded-2xl p-8 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-gray-800">Customer Reviews</h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl font-bold">{{ number_format($product['rating'], 1) }}</span>
                                    <div class="flex flex-col">
                                        <div class="star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product['rating']))
                                                    <span class="star active">★</span>
                                                @elseif($i == ceil($product['rating']) && $product['rating'] % 1 >= 0.5)
                                                    <span class="star half">★</span>
                                                @else
                                                    <span class="star">★</span>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600">Based on {{ $product['review_count'] }}
                                            review{{ $product['review_count'] != 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                            </div>

                            @include('customer.products.partials.reviews', ['reviews' => $reviews ?? collect()])

                            @if (!isset($reviews) || $reviews->count() == 0)
                                <div class="text-center py-12">
                                    <i class="fas fa-comment text-gray-300 text-5xl mb-4"></i>
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">No Reviews Yet</h4>
                                    <p class="text-gray-600">Be the first to review this product!</p>
                                    <button
                                        class="mt-4 px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                        Write a Review
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- Related Products Section -->
            @if (isset($relatedProducts) && count($relatedProducts) > 0)
                <section class="py-16 bg-gray-50">
                    <div class="max-w-7xl mx-auto px-4">
                        <h2 class="text-2xl font-bold text-gray-800 mb-8">You May Also Like</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($relatedProducts as $relatedProduct)
                                <div
                                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 product-card">
                                    <a href="{{ route('customer.products.details', $relatedProduct['slug']) }}"
                                        class="block">
                                        <div class="aspect-square overflow-hidden">
                                            <img src="{{ asset('storage/' . $relatedProduct['main_image']) }}"
                                                alt="{{ $relatedProduct['name'] }}"
                                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                                onerror="this.src='/images/placeholder-product.jpg'">
                                        </div>
                                        <div class="p-4">
                                            <h3
                                                class="font-semibold text-gray-800 mb-2 line-clamp-2 hover:text-amber-700 transition-colors">
                                                {{ $relatedProduct['name'] }}
                                            </h3>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span
                                                    class="text-lg font-bold text-amber-800">₹{{ number_format($relatedProduct['price'], 0) }}</span>
                                                @if ($relatedProduct['compare_price'] && $relatedProduct['compare_price'] > $relatedProduct['price'])
                                                    <span
                                                        class="text-sm text-gray-400 line-through">₹{{ number_format($relatedProduct['compare_price'], 0) }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="star-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($relatedProduct['rating']))
                                                            <span class="star active">★</span>
                                                        @elseif($i == ceil($relatedProduct['rating']) && $relatedProduct['rating'] % 1 >= 0.5)
                                                            <span class="star half">★</span>
                                                        @else
                                                            <span class="star">★</span>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500">({{ $relatedProduct['review_count'] }})</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @else
            <!-- Product Not Found -->
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Product Not Found</h3>
                        <p class="text-gray-600 mb-6">The product you're looking for doesn't exist or has been removed.</p>
                        <a href="{{ route('customer.products.list') }}"
                            class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                            Browse Products
                        </a>
                    </div>
                </div>
            </section>
        @endif
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <!-- CSRF Token setup -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            // Product data from PHP
            const productData = @json($product);
            const availableVariants = @json($product['variants'] ?? []);
            const attributeGroups = @json($product['attribute_groups'] ?? []);
            const productImages = @json($product['images'] ?? []);

            // Cart state
            let selectedAttributes = {};
            let selectedVariant = null;
            let currentPrice = {{ $product['price'] ?? 0 }};
            let currentQuantity = 1;
            let maxQuantity = 10;
            let isInWishlist = false;

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Product detail page loaded with:', productData);

                // Configure Axios
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content');
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

                // Initialize variant selection
                initializeVariantSelection();

                // Setup tab switching event listeners
                setupEventListeners();

                // Check wishlist status
                checkWishlistStatus();

                // Initialize quantity buttons state
                updateQuantityButtons();

                // Load initial images
                loadProductImages();
            });

            // Load product images
            function loadProductImages() {
                const thumbnailGallery = document.getElementById('thumbnailGallery');
                if (!thumbnailGallery) return;

                // Clear existing thumbnails
                thumbnailGallery.innerHTML = '';

                // Determine which images to show
                let imagesToShow = [];
                if (selectedVariant && selectedVariant.images && selectedVariant.images.length > 0) {
                    imagesToShow = selectedVariant.images;
                } else if (productImages && productImages.length > 0) {
                    imagesToShow = productImages;
                }

                // Show placeholder if no images
                if (imagesToShow.length === 0) {
                    const mainImage = document.getElementById('mainImage');
                    if (mainImage) {
                        mainImage.src = '/images/placeholder-product.jpg';
                    }
                    return;
                }

                // Set main image
                const mainImage = document.getElementById('mainImage');
                if (mainImage && imagesToShow[0]) {
                    const imgPath = isArray(imagesToShow[0]) ? imagesToShow[0].url : imagesToShow[0];
                    mainImage.src = imgPath.startsWith('http') ? imgPath : '/storage/' + imgPath;
                }

                // Create thumbnails
                imagesToShow.forEach((image, index) => {
                    const imgPath = isArray(image) ? image.url : image;
                    const fullPath = imgPath.startsWith('http') ? imgPath : '/storage/' + imgPath;

                    const thumbnailDiv = document.createElement('div');
                    thumbnailDiv.className =
                        `thumbnail-item rounded-xl overflow-hidden border-2 ${index === 0 ? 'border-amber-500' : 'border-gray-200'} p-2 cursor-pointer transition-all duration-300 hover:scale-105 hover:border-amber-500`;
                    thumbnailDiv.dataset.image = fullPath;

                    thumbnailDiv.innerHTML = `
                        <img src="${fullPath}"
                             alt="${productData.name} - View ${index + 1}"
                             class="w-full h-24 object-cover rounded-lg"
                             onclick="changeMainImage(this)"
                             onerror="this.src='/images/placeholder-product.jpg'">
                    `;

                    thumbnailGallery.appendChild(thumbnailDiv);
                });
            }

            // Helper function to check if variable is array
            function isArray(variable) {
                return Array.isArray(variable) || (typeof variable === 'object' && variable !== null && 'url' in variable);
            }

            // Initialize variant selection - AUTO SELECT DEFAULT VARIANT
            function initializeVariantSelection() {
                if (availableVariants.length > 0 && attributeGroups) {
                    // Find the default variant (is_default = true or first variant)
                    let defaultVariant = availableVariants.find(v => v.is_default) || availableVariants[0];

                    if (defaultVariant) {
                        // Clear any previous selections
                        selectedAttributes = {};

                        // Get attributes for default variant
                        if (defaultVariant.attributes && defaultVariant.attributes.length > 0) {
                            // Group attributes by name
                            const variantAttributes = {};
                            defaultVariant.attributes.forEach(attr => {
                                variantAttributes[attr.attribute_name] = attr.value;
                            });

                            // Select each attribute button
                            Object.entries(variantAttributes).forEach(([attributeName, attributeValue]) => {
                                selectedAttributes[attributeName] = attributeValue;

                                // Find and activate corresponding button
                                const button = document.querySelector(
                                    `[data-attribute-name="${attributeName}"][data-attribute-value="${attributeValue}"]`
                                );
                                if (button) {
                                    selectAttribute(button, attributeName, attributeValue, true);
                                }
                            });

                            // Update variant details
                            updateVariantDetails(defaultVariant);
                            updateAddToCartButton();
                        }
                    }

                    // If no variants are selected after initialization, show first available combination
                    if (Object.keys(selectedAttributes).length === 0) {
                        // Find first available combination from attribute groups
                        const firstAttributes = {};
                        Object.entries(attributeGroups).forEach(([attributeName, group]) => {
                            if (group.options && group.options.length > 0) {
                                firstAttributes[attributeName] = group.options[0].value;
                            }
                        });

                        // Select first available attributes
                        Object.entries(firstAttributes).forEach(([attributeName, attributeValue]) => {
                            const button = document.querySelector(
                                `[data-attribute-name="${attributeName}"][data-attribute-value="${attributeValue}"]`
                            );
                            if (button) {
                                selectAttribute(button, attributeName, attributeValue, true);
                            }
                        });
                    }
                }
            }

            // Setup event listeners
            function setupEventListeners() {
                // Tab switching
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const tabId = this.textContent.toLowerCase().includes('details') ? 'description' :
                            this.textContent.toLowerCase().includes('specification') ? 'specifications' :
                            'reviews';
                        switchTab(tabId);
                    });
                });
            }

            // Tab switching function
            function switchTab(tabId) {
                // Update tab buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'border-b-2', 'border-amber-600', 'text-gray-800');
                    btn.classList.add('text-gray-600');
                });

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                    content.classList.add('hidden');
                });

                // Activate selected tab
                const targetButton = Array.from(document.querySelectorAll('.tab-button')).find(btn =>
                    (tabId === 'description' && btn.textContent.includes('Details')) ||
                    (tabId === 'specifications' && btn.textContent.includes('Specifications')) ||
                    (tabId === 'reviews' && btn.textContent.includes('Reviews'))
                );

                if (targetButton) {
                    targetButton.classList.add('active', 'border-b-2', 'border-amber-600', 'text-gray-800');
                    targetButton.classList.remove('text-gray-600');
                }

                const targetContent = document.getElementById(tabId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                    targetContent.classList.add('active');
                }
            }

            // Attribute selection function
            function selectAttribute(button, attributeName, attributeValue, isInitial = false) {
                const group = button.closest('.variant-group');

                // Remove active state from all buttons in this group
                group.querySelectorAll('.attribute-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('bg-amber-600', 'text-white', 'border-amber-600');
                    btn.classList.add('border-gray-300', 'text-gray-700', 'bg-white');
                    btn.classList.remove('disabled');
                });

                // Add active state to selected button
                button.classList.add('active');
                button.classList.add('bg-amber-600', 'text-white', 'border-amber-600');
                button.classList.remove('border-gray-300', 'text-gray-700', 'bg-white');

                // Update selected attributes
                selectedAttributes[attributeName] = attributeValue;

                // Find matching variant
                const matchedVariant = findMatchingVariant();

                if (matchedVariant) {
                    selectedVariant = matchedVariant;
                    updateVariantDetails(matchedVariant);

                    // Show selected variant info
                    if (!isInitial) {
                        document.getElementById('selectedVariantInfo').classList.remove('hidden');
                        document.getElementById('selectedVariantPrice').classList.remove('hidden');

                        // Update selected variant text
                        const selectedVariantText = document.getElementById('selectedVariantText');
                        const attributesText = Object.entries(selectedAttributes)
                            .map(([key, value]) => `${key}: ${value}`)
                            .join(', ');
                        selectedVariantText.textContent = `Selected: ${attributesText}`;
                    }
                } else {
                    // If no variant matches all attributes yet
                    document.getElementById('selectedVariantInfo').classList.add('hidden');

                    // Check if we have enough attributes selected
                    const totalAttributeGroups = Object.keys(attributeGroups).length;
                    const selectedAttributeCount = Object.keys(selectedAttributes).length;

                    if (selectedAttributeCount === totalAttributeGroups) {
                        showNotification('Please select valid combination', 'error');
                    } else {
                        showVariantPriceSection();
                    }
                }

                // Update add to cart button
                updateAddToCartButton();
                // Update quantity buttons based on selected variant stock
                updateQuantityButtons();
                // Disable unavailable options
                updateAttributeAvailability();
            }

            // Update attribute button availability based on selected attributes
            function updateAttributeAvailability() {
                // Get currently selected attributes
                const currentSelected = {
                    ...selectedAttributes
                };

                // Loop through all attribute groups
                Object.entries(attributeGroups).forEach(([attributeName, group]) => {
                    // Skip if this is the currently being selected attribute
                    if (currentSelected[attributeName]) return;

                    // Loop through all options in this group
                    group.options.forEach(option => {
                        const button = document.querySelector(
                            `[data-attribute-name="${attributeName}"][data-attribute-value="${option.value}"]`
                        );

                        if (button) {
                            // Test if this option would lead to an available variant
                            const testAttributes = {
                                ...currentSelected,
                                [attributeName]: option.value
                            };
                            const isAvailable = isAttributeCombinationAvailable(testAttributes);

                            if (isAvailable) {
                                button.classList.remove('disabled');
                                button.disabled = false;
                            } else {
                                button.classList.add('disabled');
                                button.disabled = true;
                            }
                        }
                    });
                });
            }

            // Check if an attribute combination leads to an available variant
            function isAttributeCombinationAvailable(testAttributes) {
                return availableVariants.some(variant => {
                    if (!variant.attributes) return false;

                    const variantAttributes = {};
                    variant.attributes.forEach(attr => {
                        variantAttributes[attr.attribute_name] = attr.value;
                    });

                    // Check if variant matches all test attributes
                    for (const [key, value] of Object.entries(testAttributes)) {
                        if (!variantAttributes[key] || variantAttributes[key] !== value) {
                            return false;
                        }
                    }

                    return true;
                });
            }

            // Find variant that matches all selected attributes
            function findMatchingVariant() {
                const selectedAttributeCount = Object.keys(selectedAttributes).length;
                const totalAttributeGroups = Object.keys(attributeGroups).length;

                // We need all attributes selected to find exact variant
                if (selectedAttributeCount !== totalAttributeGroups) {
                    return null;
                }

                return availableVariants.find(variant => {
                    if (!variant.attributes) return false;

                    const variantAttributes = {};
                    variant.attributes.forEach(attr => {
                        variantAttributes[attr.attribute_name] = attr.value;
                    });

                    // Check if all selected attributes match variant attributes
                    for (const [key, value] of Object.entries(selectedAttributes)) {
                        if (!variantAttributes[key] || variantAttributes[key] !== value) {
                            return false;
                        }
                    }

                    return true;
                });
            }

            // Show variant price section
            function showVariantPriceSection() {
                const priceSection = document.getElementById('selectedVariantPrice');
                if (priceSection) {
                    priceSection.classList.remove('hidden');
                }

                // Update price based on available options for selected attributes
                updatePriceBasedOnSelectedAttributes();
            }

            // Update price based on partially selected attributes
            function updatePriceBasedOnSelectedAttributes() {
                // Find variants that match currently selected attributes
                const matchingVariants = availableVariants.filter(variant => {
                    if (!variant.attributes) return false;

                    const variantAttributes = {};
                    variant.attributes.forEach(attr => {
                        variantAttributes[attr.attribute_name] = attr.value;
                    });

                    // Check if variant matches all selected attributes
                    for (const [key, value] of Object.entries(selectedAttributes)) {
                        if (!variantAttributes[key] || variantAttributes[key] !== value) {
                            return false;
                        }
                    }
                    return true;
                });

                if (matchingVariants.length > 0) {
                    const prices = matchingVariants.map(v => v.price);
                    const minPrice = Math.min(...prices);
                    const maxPrice = Math.max(...prices);

                    const priceDisplay = document.getElementById('variantPriceDisplay');
                    if (priceDisplay) {
                        if (minPrice === maxPrice) {
                            priceDisplay.textContent = `${formatPrice(minPrice)}`;
                        } else {
                            priceDisplay.textContent = `${formatPrice(minPrice)} - ${formatPrice(maxPrice)}`;
                        }
                    }

                    // Show price section
                    const priceSection = document.getElementById('selectedVariantPrice');
                    if (priceSection) {
                        priceSection.classList.remove('hidden');
                    }
                }
            }

            // Update UI when variant changes
            function updateVariantDetails(variant) {
                // Update current price
                currentPrice = variant.price;

                // Update main price displays
                const currentPriceDisplay = document.getElementById('currentPriceDisplay');
                const comparePriceDisplay = document.getElementById('comparePriceDisplay');
                const discountPercentDisplay = document.getElementById('discountPercentDisplay');
                const discountBadge = document.getElementById('discountBadge');

                if (currentPriceDisplay) {
                    currentPriceDisplay.textContent = `${formatPrice(variant.price)}`;
                }

                // Update variant-specific price displays
                const variantPriceDisplay = document.getElementById('variantPriceDisplay');
                const variantComparePriceDisplay = document.getElementById('variantComparePriceDisplay');
                const variantDiscountPercent = document.getElementById('variantDiscountPercent');

                if (variantPriceDisplay) {
                    variantPriceDisplay.textContent = `${formatPrice(variant.price)}`;
                }

                // Handle discount display
                if (variant.compare_price && variant.compare_price > variant.price) {
                    const discountPercent = Math.round(((variant.compare_price - variant.price) / variant.compare_price) * 100);

                    // Update main displays
                    if (comparePriceDisplay) {
                        comparePriceDisplay.textContent = `${formatPrice(variant.compare_price)}`;
                        comparePriceDisplay.classList.remove('hidden');
                    }

                    if (discountPercentDisplay) {
                        discountPercentDisplay.textContent = `Save ${discountPercent}%`;
                        discountPercentDisplay.classList.remove('hidden');
                    }

                    if (discountBadge) {
                        discountBadge.textContent = `${discountPercent}% OFF`;
                        discountBadge.classList.remove('hidden');
                    }

                    // Update variant-specific displays
                    if (variantComparePriceDisplay) {
                        variantComparePriceDisplay.textContent = `${formatPrice(variant.compare_price)}`;
                        variantComparePriceDisplay.classList.remove('hidden');
                    }

                    if (variantDiscountPercent) {
                        variantDiscountPercent.textContent = `Save ${discountPercent}%`;
                        variantDiscountPercent.classList.remove('hidden');
                    }
                } else {
                    // Hide discount displays
                    if (comparePriceDisplay) comparePriceDisplay.classList.add('hidden');
                    if (discountPercentDisplay) discountPercentDisplay.classList.add('hidden');
                    if (discountBadge) discountBadge.classList.add('hidden');
                    if (variantComparePriceDisplay) variantComparePriceDisplay.classList.add('hidden');
                    if (variantDiscountPercent) variantDiscountPercent.classList.add('hidden');
                }

                // Update stock status
                const dynamicStockStatus = document.getElementById('dynamicStockStatus');
                const mainStockStatus = document.getElementById('stockStatus');
                if (dynamicStockStatus && variant.stock_quantity !== undefined) {
                    const isInStock = variant.stock_quantity > 0;
                    maxQuantity = Math.min(variant.stock_quantity, 10); // Update max quantity based on stock

                    dynamicStockStatus.className = `flex items-center gap-3 ${isInStock ? 'text-green-600' : 'text-red-600'}`;
                    dynamicStockStatus.innerHTML = `
                        <i class="fas ${isInStock ? 'fa-check-circle' : 'fa-times-circle'} text-xl"></i>
                        <div>
                            <p class="font-medium">
                                ${isInStock ? 'In Stock' : 'Out of Stock'}
                                ${isInStock && variant.stock_quantity ? `(${variant.stock_quantity} available)` : ''}
                            </p>
                            <p class="text-sm">${isInStock ? 'Order within next 2 hours for same day dispatch' : 'Expected restock in 7-10 days'}</p>
                        </div>
                    `;

                    // Also update main stock status
                    if (mainStockStatus) {
                        mainStockStatus.className = `${isInStock ? 'text-green-600' : 'text-red-600'} font-medium`;
                        mainStockStatus.innerHTML = `
                            <i class="fas ${isInStock ? 'fa-check-circle' : 'fa-times-circle'} mr-1"></i>
                            ${isInStock ? 'In Stock' : 'Out of Stock'}
                        `;
                    }

                    // Update action buttons
                    document.getElementById('addToCartBtn').disabled = !isInStock;
                    document.getElementById('buyNowBtn').disabled = !isInStock;
                }

                // Load variant-specific images
                loadProductImages();

                // Update total price
                updateTotalPrice();
                // Update quantity buttons
                updateQuantityButtons();
            }

            // Update add to cart button with variant info
            function updateAddToCartButton() {
                const addToCartBtn = document.getElementById('addToCartBtn');
                const buyNowBtn = document.getElementById('buyNowBtn');
                const addToCartText = document.getElementById('addToCartText');

                const variantId = getSelectedVariantId();

                if (addToCartBtn) {
                    if (variantId) {
                        if (addToCartText) {
                            addToCartText.textContent = 'Add to Cart';
                        }

                        // Check stock for this variant
                        const matchedVariant = findMatchingVariant();
                        if (matchedVariant && matchedVariant.stock_quantity !== undefined) {
                            const isInStock = matchedVariant.stock_quantity > 0;
                            addToCartBtn.disabled = !isInStock;
                            buyNowBtn.disabled = !isInStock;
                        }
                    } else {
                        // Check if all attributes are selected
                        const totalAttributeGroups = Object.keys(attributeGroups).length;
                        const selectedAttributeCount = Object.keys(selectedAttributes).length;

                        if (selectedAttributeCount > 0 && selectedAttributeCount < totalAttributeGroups) {
                            if (addToCartText) {
                                addToCartText.textContent = 'Select Complete Options';
                            }
                        } else if (selectedAttributeCount === 0 && totalAttributeGroups > 0) {
                            if (addToCartText) {
                                addToCartText.textContent = 'Select Options';
                            }
                        }
                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                    }
                }
            }

            // Get selected variant ID
            function getSelectedVariantId() {
                const matchedVariant = findMatchingVariant();
                return matchedVariant ? matchedVariant.id : null;
            }

            // QUANTITY FUNCTIONS
            function decreaseQuantity() {
                if (currentQuantity > 1) {
                    currentQuantity--;
                    updateQuantityDisplay();
                    updateTotalPrice();
                    updateQuantityButtons();
                }
            }

            function increaseQuantity() {
                const currentMaxQuantity = selectedVariant ? Math.min(selectedVariant.stock_quantity || 10, 10) : maxQuantity;
                if (currentQuantity < currentMaxQuantity) {
                    currentQuantity++;
                    updateQuantityDisplay();
                    updateTotalPrice();
                    updateQuantityButtons();
                }
            }

            function updateQuantityDisplay() {
                const quantityDisplay = document.getElementById('quantityDisplay');
                const quantityInput = document.getElementById('quantity');

                if (quantityDisplay) {
                    quantityDisplay.textContent = currentQuantity;
                }
                if (quantityInput) {
                    quantityInput.value = currentQuantity;
                }
            }

            function updateQuantityButtons() {
                const decreaseBtn = document.getElementById('decreaseQty');
                const increaseBtn = document.getElementById('increaseQty');

                // Get the maximum quantity allowed
                const currentMaxQuantity = selectedVariant ? Math.min(selectedVariant.stock_quantity || 10, 10) : maxQuantity;

                if (decreaseBtn) {
                    decreaseBtn.disabled = currentQuantity <= 1;
                }

                if (increaseBtn) {
                    increaseBtn.disabled = currentQuantity >= currentMaxQuantity;
                }
            }

            function updateTotalPrice() {
                const totalPriceElement = document.getElementById('totalPriceValue');
                if (totalPriceElement) {
                    const total = currentPrice * currentQuantity;
                    totalPriceElement.textContent = formatPrice(total);
                }
            }

            // Add to cart function
            async function addToCart() {
                const variantId = getSelectedVariantId();
                if (!variantId) {
                    showNotification('Please select product options', 'error');
                    return;
                }

                const addToCartBtn = document.getElementById('addToCartBtn');
                const addToCartText = document.getElementById('addToCartText');
                const originalText = addToCartText ? addToCartText.textContent : 'Add to Cart';
                const originalHTML = addToCartBtn.innerHTML;

                // Disable button and show loading
                addToCartBtn.disabled = true;
                if (addToCartText) {
                    addToCartText.textContent = 'Adding...';
                }

                try {
                    // Get attributes for the selected variant
                    const attributes = getSelectedAttributes();

                    const response = await axios.post('{{ route('customer.cart.add') }}', {
                        variant_id: variantId,
                        quantity: currentQuantity,
                        attributes: attributes
                    });

                    console.log('Add to cart response:', response.data);

                    if (response.data.success) {
                        // Show success message
                        showNotification(response.data.message || 'Added to cart successfully!', 'success');

                        // Update button to show "Added"
                        addToCartBtn.innerHTML = '<i class="fas fa-check"></i> Added';
                        addToCartBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                        addToCartBtn.classList.remove('bg-gray-900', 'hover:bg-gray-800');

                        // Update cart count in header
                        let cartCount = 0;
                        if (response.data.cart_count !== undefined) {
                            cartCount = response.data.cart_count;
                        } else if (response.data.data && response.data.data.cart_count !== undefined) {
                            cartCount = response.data.data.cart_count;
                        }

                        // Update cart count
                        updateCartCount(cartCount);

                        // Reset button after 2 seconds
                        setTimeout(() => {
                            addToCartBtn.innerHTML = originalHTML;
                            addToCartBtn.disabled = false;
                            addToCartBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                            addToCartBtn.classList.add('bg-gray-900', 'hover:bg-gray-800');
                            if (addToCartText) {
                                addToCartText.textContent = originalText;
                            }
                        }, 2000);
                    } else {
                        throw new Error(response.data.message || 'Failed to add to cart');
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);

                    showNotification(
                        error.response?.data?.message ||
                        error.message ||
                        'Failed to add to cart. Please try again.',
                        'error'
                    );

                    // Reset button
                    addToCartBtn.innerHTML = originalHTML;
                    addToCartBtn.disabled = false;
                    if (addToCartText) {
                        addToCartText.textContent = originalText;
                    }
                }
            }

            // Toggle wishlist
            async function toggleWishlist() {
                const wishlistBtn = document.getElementById('wishlistBtn');
                const wishlistText = document.getElementById('wishlistText');
                const productId = productData.id;

                try {
                    const response = await axios.post('{{ route('customer.wishlist.add') }}', {
                        product_id: productId
                    });

                    if (response.data.success) {
                        isInWishlist = !isInWishlist;

                        if (isInWishlist) {
                            // Added to wishlist
                            wishlistBtn.innerHTML =
                                '<i class="fas fa-heart text-red-500"></i> <span id="wishlistText">Added to Wishlist</span>';
                            wishlistBtn.classList.add('border-red-500', 'text-red-600');
                            wishlistBtn.classList.remove('border-amber-600', 'text-amber-600');
                            showNotification('Added to wishlist!', 'success');
                        } else {
                            // Removed from wishlist
                            wishlistBtn.innerHTML =
                                '<i class="fas fa-heart"></i> <span id="wishlistText">Add to Wishlist</span>';
                            wishlistBtn.classList.remove('border-red-500', 'text-red-600');
                            wishlistBtn.classList.add('border-amber-600', 'text-amber-600');
                            showNotification('Removed from wishlist', 'info');
                        }
                    }
                } catch (error) {
                    console.error('Error updating wishlist:', error);
                    showNotification('Failed to update wishlist', 'error');
                }
            }

            // Buy now function
            function buyNow() {
                const variantId = getSelectedVariantId();
                if (!variantId) {
                    showNotification('Please select product options', 'error');
                    return;
                }

                // Redirect to checkout with product data
                const checkoutUrl = new URL('{{ route('customer.checkout.index') }}');
                checkoutUrl.searchParams.append('product_id', productData.id);
                checkoutUrl.searchParams.append('variant_id', variantId);
                checkoutUrl.searchParams.append('quantity', currentQuantity);

                window.location.href = checkoutUrl.toString();
            }

            // Check wishlist status
            async function checkWishlistStatus() {
                const productId = productData.id;

                try {
                    const response = await axios.get(`/wishlist/check/${productId}`);
                    if (response.data.success) {
                        isInWishlist = response.data.in_wishlist;
                        updateWishlistButton();
                    }
                } catch (error) {
                    console.error('Error checking wishlist:', error);
                }
            }

            function updateWishlistButton() {
                const wishlistBtn = document.getElementById('wishlistBtn');
                if (isInWishlist) {
                    wishlistBtn.innerHTML =
                        '<i class="fas fa-heart text-red-500"></i> <span id="wishlistText">Added to Wishlist</span>';
                    wishlistBtn.classList.add('border-red-500', 'text-red-600');
                    wishlistBtn.classList.remove('border-amber-600', 'text-amber-600');
                }
            }

            // Helper functions
            function formatPrice(price) {
                return '₹' + new Intl.NumberFormat('en-IN').format(price);
            }

            function getSelectedAttributes() {
                return selectedAttributes;
            }

            // Image functions
            function changeMainImage(thumbnail) {
                const mainImage = document.getElementById('mainImage');
                const thumbnailItem = thumbnail.closest('.thumbnail-item');
                const imageUrl = thumbnailItem.dataset.image || thumbnail.src;

                // Update main image
                mainImage.src = imageUrl;

                // Update active thumbnail
                document.querySelectorAll('.thumbnail-item').forEach(item => {
                    item.classList.remove('border-amber-500');
                    item.classList.add('border-gray-200');
                });
                thumbnailItem.classList.remove('border-gray-200');
                thumbnailItem.classList.add('border-amber-500');
            }

            function toggleZoom(image) {
                image.classList.toggle('zoomed');
                if (image.classList.contains('zoomed')) {
                    image.style.cursor = 'zoom-out';
                } else {
                    image.style.cursor = 'zoom-in';
                }
            }

            // Update cart count in header
            function updateCartCount(count) {
                // Use your global function
                if (typeof updateCartCountGlobal === 'function') {
                    updateCartCountGlobal(count);
                } else {
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(el => {
                        el.textContent = count;
                        if (count > 0) {
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    });
                }
            }

            // Show notification
            function showNotification(message, type = 'success') {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.custom-notification');
                existingNotifications.forEach(notification => notification.remove());

                // Create notification element
                const notification = document.createElement('div');
                notification.className =
                    `custom-notification fixed top-4 right-4 px-6 py-3 rounded-full shadow-lg z-50 ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
                notification.textContent = message;

                // Add to body
                document.body.appendChild(notification);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.classList.add('fade-out');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 500);
                }, 3000);
            }
        </script>
    @endpush
