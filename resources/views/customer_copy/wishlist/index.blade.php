@extends('customer.layouts.master')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('styles')
<style>
    .wishlist-item {
        transition: all 0.3s ease;
    }

    .wishlist-item-removing {
        opacity: 0.5;
        transform: scale(0.95);
    }

    .product-image {
        transition: transform 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    .discount-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #dc2626;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .out-of-stock {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #6b7280;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .wishlist-selector {
        border: 2px solid #d97706;
        background: #fef3c7;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('customer.account.profile') }}" class="text-amber-600 hover:text-amber-800">My Account</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600">My Wishlist</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-amber-700"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ Auth::guard('customer')->user()->name }}</h3>
                        <p class="text-sm text-gray-600">{{ Auth::guard('customer')->user()->email ?? Auth::guard('customer')->user()->mobile }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('customer.account.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700">
                        <i class="fas fa-heart"></i>
                        <span>My Wishlist</span>
                        @if($wishlistCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $wishlistCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.orders') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                        @php
                            $ordersCount = \App\Models\Order::where('customer_id', Auth::guard('customer')->id())->count();
                        @endphp
                        @if($ordersCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $ordersCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.addresses') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Addresses</span>
                    </a>

                    <a href="{{ route('customer.account.change-password') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>

                    <form method="POST" action="{{ route('customer.logout') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 w-full">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">My Wishlist</h2>
                        <p class="text-gray-600 mt-1">
                            {{ $wishlistCount }} item{{ $wishlistCount != 1 ? 's' : '' }} saved
                            @if($totalPrice > 0)
                            • Total: <span class="font-bold text-amber-700">₹{{ number_format($totalPrice, 2) }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if($wishlistCount > 0)
                        <button onclick="moveAllToCart()"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-shopping-cart mr-2"></i>Move All to Cart
                        </button>

                        <button onclick="clearWishlist()"
                                class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50">
                            <i class="fas fa-trash mr-2"></i>Clear All
                        </button>

                        <button onclick="shareWishlist()"
                                class="px-4 py-2 border border-amber-600 text-amber-600 rounded-lg hover:bg-amber-50">
                            <i class="fas fa-share-alt mr-2"></i>Share
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Wishlist Items -->
                @if($wishlistCount > 0)
                <div class="space-y-6">
                    <!-- Bulk Actions -->
                    <div class="bg-amber-50 p-4 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 text-amber-600 rounded">
                            <label for="selectAll" class="text-gray-700">Select All</label>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="moveSelectedToCart()"
                                    class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm">
                                <i class="fas fa-shopping-cart mr-2"></i>Move Selected to Cart
                            </button>
                            <button onclick="removeSelected()"
                                    class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 text-sm">
                                <i class="fas fa-trash mr-2"></i>Remove Selected
                            </button>
                        </div>
                    </div>

                    <!-- Items Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($wishlistItems as $item)
                        <div class="wishlist-item bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow"
                             data-item-id="{{ $item->id }}">
                            <div class="relative">
                                <!-- Product Image -->
                                <a href="{{ route('customer.products.details', $item->variant->product->slug ?? '#') }}"
                                   class="block aspect-square overflow-hidden">
                                    @php
                                        $image = null;
                                        if ($item->variant && $item->variant->images) {
                                            $images = is_string($item->variant->images) ? json_decode($item->variant->images, true) : $item->variant->images;
                                            $image = is_array($images) && !empty($images) ? $images[0] : null;
                                        }
                                    @endphp

                                    @if($image)
                                    <img src="{{ is_array($image) ? ($image['url'] ?? asset('storage/' . $image)) : asset('storage/' . $image) }}"
                                         alt="{{ $item->variant->product->name ?? 'Product' }}"
                                         class="w-full h-full object-cover product-image">
                                    @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-gem text-3xl text-gray-300"></i>
                                    </div>
                                    @endif

                                    <!-- Discount Badge -->
                                    @php
                                        $price = $item->variant->price ?? 0;
                                        $comparePrice = $item->variant->compare_price ?? 0;
                                        $discount = 0;
                                        if ($comparePrice > $price) {
                                            $discount = round((($comparePrice - $price) / $comparePrice) * 100);
                                        }
                                    @endphp
                                    @if($discount > 0)
                                    <span class="discount-badge">-{{ $discount }}%</span>
                                    @endif

                                    <!-- Out of Stock -->
                                    @if(($item->variant->stock_quantity ?? 0) <= 0)
                                    <span class="out-of-stock">Out of Stock</span>
                                    @endif
                                </a>

                                <!-- Remove Button -->
                                <button onclick="removeFromWishlist({{ $item->id }})"
                                        class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-50 text-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Product Details -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-2 truncate">
                                    <a href="{{ route('customer.products.details', $item->variant->product->slug ?? '#') }}"
                                       class="hover:text-amber-700">
                                        {{ $item->variant->product->name ?? 'Product Name' }}
                                    </a>
                                </h3>

                                @if($item->variant->sku ?? false)
                                <p class="text-sm text-gray-500 mb-3">SKU: {{ $item->variant->sku }}</p>
                                @endif

                                <!-- Price -->
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-lg font-bold text-amber-700">
                                        ₹{{ number_format($price, 2) }}
                                    </span>
                                    @if($comparePrice > $price)
                                    <span class="text-sm text-gray-500 line-through">
                                        ₹{{ number_format($comparePrice, 2) }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-3">
                                    <input type="checkbox"
                                           class="item-checkbox w-5 h-5 text-amber-600 rounded"
                                           value="{{ $item->id }}">

                                    @if(($item->variant->stock_quantity ?? 0) > 0)
                                    <button onclick="moveToCart({{ $item->id }})"
                                            class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                    </button>
                                    @else
                                    <button disabled
                                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-ban mr-2"></i>Out of Stock
                                    </button>
                                    @endif
                                </div>

                                <!-- Added Date -->
                                <p class="text-xs text-gray-500 mt-3">
                                    <i class="far fa-clock mr-1"></i>
                                    Added {{ $item->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($wishlistItems->hasPages())
                    <div class="mt-8">
                        {{ $wishlistItems->links() }}
                    </div>
                    @endif
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-3xl text-amber-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Your wishlist is empty</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Save your favorite jewelry pieces here to keep track of what you love.
                        Click the heart icon on any product to add it to your wishlist.
                    </p>
                    <div class="space-y-4">
                        <a href="{{ route('customer.home.index') }}"
                           class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-600 to-amber-800 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:shadow-xl">
                            <i class="fas fa-gem mr-2"></i>
                            Browse Jewelry Collection
                        </a>
                        <div class="text-sm text-gray-500">
                            <p class="mb-2">How to add items to wishlist:</p>
                            <ul class="space-y-1">
                                <li class="flex items-center gap-2 justify-center">
                                    <i class="fas fa-heart text-amber-500"></i>
                                    <span>Click the heart icon on any product</span>
                                </li>
                                <li class="flex items-center gap-2 justify-center">
                                    <i class="fas fa-sync-alt text-amber-500"></i>
                                    <span>View all saved items here anytime</span>
                                </li>
                                <li class="flex items-center gap-2 justify-center">
                                    <i class="fas fa-shopping-cart text-amber-500"></i>
                                    <span>Move items to cart when ready to buy</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Wishlist Summary -->
                @if($wishlistCount > 0)
                <div class="mt-8 p-6 bg-gradient-to-r from-amber-50 to-amber-100 rounded-2xl">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Wishlist Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Items</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $wishlistCount }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Estimated Total</p>
                            <p class="text-2xl font-bold text-amber-700">₹{{ number_format($totalPrice, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Average Price</p>
                            <p class="text-2xl font-bold text-gray-800">
                                ₹{{ $wishlistCount > 0 ? number_format($totalPrice / $wishlistCount, 2) : '0.00' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Wishlist management functions
function removeFromWishlist(itemId) {
    const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
    if (itemElement) {
        itemElement.classList.add('wishlist-item-removing');
    }

    fetch('{{ route("customer.wishlist.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ item_id: itemId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            setTimeout(() => {
                if (itemElement) {
                    itemElement.remove();
                }
                updateWishlistCount(data.count);
                showNotification(data.message || 'Item removed from wishlist', 'success');

                // Check if wishlist is now empty
                if (data.count === 0) {
                    location.reload();
                }
            }, 300);
        } else {
            if (itemElement) {
                itemElement.classList.remove('wishlist-item-removing');
            }
            showNotification(data.message || 'Failed to remove item', 'error');
        }
    })
    .catch(error => {
        if (itemElement) {
            itemElement.classList.remove('wishlist-item-removing');
        }
        showNotification('Failed to remove item', 'error');
    });
}

function moveToCart(itemId) {
    fetch('{{ route("customer.wishlist.move-to-cart") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ item_id: itemId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from wishlist
            const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
            if (itemElement) {
                itemElement.remove();
            }

            updateWishlistCount(data.count);
            showNotification(data.message || 'Item added to cart successfully', 'success');

            // Update cart count (implement your cart count update logic)
            updateCartCount();

            // Check if wishlist is now empty
            if (data.count === 0) {
                location.reload();
            }
        } else {
            showNotification(data.message || 'Failed to add to cart', 'error');
        }
    })
    .catch(error => {
        showNotification('Failed to add to cart', 'error');
    });
}

function moveAllToCart() {
    if (!confirm('Move all items to cart?')) return;

    fetch('{{ route("customer.wishlist.move-all-to-cart") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'All items moved to cart', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Failed to move items to cart', 'error');
        }
    })
    .catch(error => {
        showNotification('Failed to move items to cart', 'error');
    });
}

function clearWishlist() {
    if (!confirm('Are you sure you want to clear your wishlist?')) return;

    fetch('{{ route("customer.wishlist.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Wishlist cleared successfully', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Failed to clear wishlist', 'error');
        }
    })
    .catch(error => {
        showNotification('Failed to clear wishlist', 'error');
    });
}

// Bulk selection
document.getElementById('selectAll')?.addEventListener('change', function(e) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
});

function getSelectedItems() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function moveSelectedToCart() {
    const selectedIds = getSelectedItems();
    if (selectedIds.length === 0) {
        showNotification('Please select items first', 'warning');
        return;
    }

    if (!confirm(`Move ${selectedIds.length} item(s) to cart?`)) return;

    // For now, move items one by one
    let processed = 0;
    selectedIds.forEach(itemId => {
        moveToCart(itemId);
        processed++;

        // If all items processed, uncheck select all
        if (processed === selectedIds.length) {
            document.getElementById('selectAll').checked = false;
        }
    });
}

function removeSelected() {
    const selectedIds = getSelectedItems();
    if (selectedIds.length === 0) {
        showNotification('Please select items first', 'warning');
        return;
    }

    if (!confirm(`Remove ${selectedIds.length} item(s) from wishlist?`)) return;

    fetch('{{ route("customer.wishlist.remove.multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ item_ids: selectedIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove selected items from DOM
            selectedIds.forEach(itemId => {
                const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                if (itemElement) itemElement.remove();
            });

            updateWishlistCount(data.count);
            showNotification(data.message || 'Selected items removed', 'success');

            // Uncheck select all
            document.getElementById('selectAll').checked = false;

            // Check if wishlist is now empty
            if (data.count === 0) {
                location.reload();
            }
        } else {
            showNotification(data.message || 'Failed to remove items', 'error');
        }
    })
    .catch(error => {
        showNotification('Failed to remove items', 'error');
    });
}

function shareWishlist() {
    fetch('{{ route("customer.wishlist.share", $wishlist->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Copy to clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(data.share_url)
                    .then(() => {
                        showNotification('Share link copied to clipboard!', 'success');
                    })
                    .catch(() => {
                        fallbackCopyToClipboard(data.share_url);
                    });
            } else {
                fallbackCopyToClipboard(data.share_url);
            }
        } else {
            showNotification(data.message || 'Failed to share wishlist', 'error');
        }
    })
    .catch(error => {
        showNotification('Failed to share wishlist', 'error');
    });
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showNotification('Share link copied to clipboard!', 'success');
        } else {
            showNotification('Failed to copy share link', 'error');
        }
    } catch (err) {
        showNotification('Failed to copy share link', 'error');
    }

    document.body.removeChild(textArea);
}

function updateWishlistCount(count) {
    // Update wishlist count in sidebar
    const countElement = document.querySelector('a[href*="wishlist"] .bg-amber-600');
    if (countElement) {
        if (count > 0) {
            countElement.textContent = count;
        } else {
            countElement.remove();
        }
    }

    // Update header count
    const headerCount = document.querySelector('h2 + p .text-gray-600');
    if (headerCount) {
        const text = `${count} item${count != 1 ? 's' : ''} saved`;
        const totalMatch = headerCount.innerHTML.match(/• Total:.*$/);
        if (totalMatch) {
            headerCount.innerHTML = text + ' ' + totalMatch[0];
        } else {
            headerCount.innerHTML = text;
        }
    }
}

function updateCartCount() {
    // Implement cart count update based on your cart implementation
    // Example:
    // fetch('/cart/count')
    //     .then(response => response.json())
    //     .then(data => {
    //         // Update cart count in UI
    //     });
}

function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notif => notif.remove());

    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 px-6 py-3 rounded-full shadow-lg z-50 animate-slide-in ${
        type === 'success' ? 'bg-green-100 text-green-800' :
        type === 'error' ? 'bg-red-100 text-red-800' :
        type === 'warning' ? 'bg-amber-100 text-amber-800' :
        'bg-blue-100 text-blue-800'
    }`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' :
                         type === 'error' ? 'exclamation-circle' :
                         type === 'warning' ? 'exclamation-triangle' :
                         'info-circle'} mr-2"></i>
        ${message}
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Add this to your product pages for adding to wishlist
function addToWishlist(productVariantId) {
    fetch('{{ route("customer.wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_variant_id: productVariantId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Added to wishlist!', 'success');
            updateWishlistCount(data.count);
        } else {
            showNotification(data.message || 'Failed to add to wishlist', 'warning');
        }
    })
    .catch(error => {
        showNotification('Failed to add to wishlist', 'error');
    });
}
</script>
@endpush
