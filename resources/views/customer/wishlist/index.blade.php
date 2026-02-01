@extends('customer.layouts.master')

@section('title', 'My Wishlist | Craft Fashion')

@section('content')
<!-- Page Header -->
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold font-playfair text-dark mb-2">My Wishlist</h1>
                <nav class="flex text-sm text-secondary">
                    <a href="{{ route('customer.home.index') }}" class="hover:text-primary transition-colors">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('customer.account.profile') }}" class="hover:text-primary transition-colors">Account</a>
                    <span class="mx-2">/</span>
                    <span class="text-dark font-medium">Wishlist</span>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="p-6 bg-primary/5 border-b border-gray-100 text-center">
                        <div class="w-20 h-20 bg-white rounded-full mx-auto shadow-sm flex items-center justify-center mb-3">
                            <span class="text-3xl font-bold text-primary">{{ substr(auth('customer')->user()->name, 0, 1) }}</span>
                        </div>
                        <h3 class="font-bold text-dark text-lg truncate">{{ auth('customer')->user()->name }}</h3>
                        <p class="text-secondary text-sm truncate">{{ auth('customer')->user()->email }}</p>
                    </div>
                    
                    <nav class="p-2 space-y-1">
                        <a href="{{ route('customer.account.profile') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-user w-6"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('customer.account.orders') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-shopping-bag w-6"></i>
                            <span>My Orders</span>
                        </a>
                        <a href="{{ route('customer.account.addresses') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-map-marker-alt w-6"></i>
                            <span>Addresses</span>
                        </a>
                        <a href="{{ route('customer.wishlist.index') }}" class="flex items-center px-4 py-3 bg-primary/5 text-primary font-medium rounded-xl transition-colors">
                            <i class="fas fa-heart w-6"></i>
                            <span>Wishlist</span>
                        </a>
                        <a href="{{ route('customer.account.change-password') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-lock w-6"></i>
                            <span>Change Password</span>
                        </a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="border-t border-gray-100 mt-2 pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-red-500 hover:bg-red-50 font-medium rounded-xl transition-colors">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h3 class="font-bold text-lg text-dark">My Wishlist</h3>
                            <p class="text-secondary text-sm" id="wishlist-count-text">{{ $wishlistCount }} items in your wishlist</p>
                        </div>
                        @if($wishlistCount > 0)
                            <form action="{{ route('customer.wishlist.clear') }}" method="POST" onsubmit="return confirm('Clear all items from wishlist?')">
                                @csrf
                                <button type="submit" class="text-red-500 text-sm font-medium hover:underline">Clear All</button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        @if($wishlistItems->isEmpty())
                            <div class="text-center py-20">
                                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-heart text-gray-200 text-4xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-dark mb-2">Your wishlist is empty</h3>
                                <p class="text-secondary mb-8">Save items that you like in your wishlist and they will show up here.</p>
                                <a href="{{ route('customer.products.list') }}" class="btn-primary inline-block px-8 py-3 rounded-full text-white bg-primary font-bold hover:bg-dark transition-all">
                                    Start Shopping
                                </a>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="wishlist-grid">
                                @foreach($wishlistItems as $item)
                                    @php
                                        $variant = $item->variant;
                                        $product = $variant->product;
                                    @endphp
                                    <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full wishlist-item" data-id="{{ $item->id }}" data-variant-id="{{ $item->product_variant_id }}">
                                        <!-- Product Image -->
                                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                                            @if($variant->display_image)
                                                <img src="{{ $variant->display_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <i class="fas fa-image text-4xl"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Remove Button -->
                                            <button onclick="removeFromWishlist({{ $item->id }})" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm z-10" title="Remove from Wishlist">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="p-4 flex-grow flex flex-col">
                                            <div class="mb-2">
                                                <p class="text-xs text-secondary uppercase tracking-wider mb-1">{{ $product->category->name ?? 'Collection' }}</p>
                                                <h4 class="font-bold text-dark hover:text-primary transition-colors text-sm truncate">
                                                    <a href="{{ route('customer.products.details', $product->slug) }}">{{ $product->name }}</a>
                                                </h4>
                                            </div>

                                            <div class="flex items-center gap-2 mb-4">
                                                <span class="font-bold text-primary">₹{{ number_format($variant->price ?? $product->price, 2) }}</span>
                                                @if($variant->compare_price && $variant->compare_price > $variant->price)
                                                    <span class="text-xs text-secondary line-through">₹{{ number_format($variant->compare_price, 2) }}</span>
                                                @endif
                                            </div>

                                            <div class="mt-auto pt-4 border-t border-gray-50 flex gap-2">
                                                <button onclick="moveToCart({{ $item->id }})" class="flex-grow bg-dark text-white text-xs font-bold py-3 rounded-lg hover:bg-primary transition-colors flex items-center justify-center gap-2">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    Move to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-8">
                                {{ $wishlistItems->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function removeFromWishlist(itemId) {
        Swal.fire({
            title: 'Remove from wishlist?',
            text: "You can add it back later if you change your mind.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c98f83',
            cancelButtonColor: '#747471',
            confirmButtonText: 'Yes, remove it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await axios.post("{{ route('customer.wishlist.remove') }}", {
                        item_id: itemId
                    });
                    
                     if (response.data.success) {
                         const itemElement = document.querySelector(`.wishlist-item[data-id="${itemId}"]`);
                         const variantId = itemElement.dataset.variantId;
                         itemElement.classList.add('opacity-0', 'scale-90');
                         setTimeout(() => {
                             itemElement.remove();
                             // Update count text
                             const countText = document.getElementById('wishlist-count-text');
                             const currentCount = parseInt(countText.innerText);
                             countText.innerText = (currentCount - 1) + ' items in your wishlist';
                             
                             // If grid is empty, reload for empty state
                             if (document.querySelectorAll('.wishlist-item').length === 0) {
                                 location.reload();
                             }
                         }, 300);
                         
                         if(typeof updateWishlistCount === 'function') {
                             updateWishlistCount(response.data.count);
                         }
                         
                         // Broadcast update
                         if (typeof wishlistChannel !== 'undefined') {
                             wishlistChannel.postMessage({
                                 type: 'wishlist_updated',
                                 variantId: variantId,
                                 count: response.data.count,
                                 status: 'removed'
                             });
                         }

                         toastr.success(response.data.message);
                     }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Error removing item');
                }
            }
        });
    }

    async function moveToCart(itemId) {
        try {
            const response = await axios.post("{{ route('customer.wishlist.move-to-cart') }}", {
                item_id: itemId
            });
            
             if (response.data.success) {
                 const itemElement = document.querySelector(`.wishlist-item[data-id="${itemId}"]`);
                 const variantId = itemElement.dataset.variantId;
                 itemElement.classList.add('opacity-0', 'scale-90');
                 setTimeout(() => {
                     itemElement.remove();
                     const countText = document.getElementById('wishlist-count-text');
                     if (countText) {
                         const currentCount = parseInt(countText.innerText);
                         countText.innerText = (currentCount - 1) + ' items in your wishlist';
                     }
                     if (document.querySelectorAll('.wishlist-item').length === 0) {
                         location.reload();
                     }
                 }, 300);
                 
                 if(typeof updateWishlistCount === 'function') {
                     updateWishlistCount(response.data.count);
                 }

                 // Broadcast update
                 if (typeof wishlistChannel !== 'undefined') {
                     wishlistChannel.postMessage({
                         type: 'wishlist_updated',
                         variantId: variantId,
                         count: response.data.count,
                         status: 'removed'
                     });
                 }
                 
                 toastr.success(response.data.message);
                 // Trigger cart count update if needed
                 if (typeof updateCartCount === 'function') updateCartCount(response.data.cart_count);
             }
        } catch (error) {
            toastr.error(error.response?.data?.message || 'Error moving item to cart');
        }
    }
</script>
@endpush
