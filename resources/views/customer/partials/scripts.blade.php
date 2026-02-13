<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Configure Axios Defaults
    if (window.axios) {
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }
    }
    
    // Mobile menu toggle
    const mobileBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Mobile Categories Toggle
    const mobileCatBtn = document.getElementById('mobile-categories-button');
    const mobileCatMenu = document.getElementById('mobile-categories-menu');
    const mobileCatChevron = document.getElementById('mobile-categories-chevron');

    if (mobileCatBtn && mobileCatMenu) {
        mobileCatBtn.addEventListener('click', function() {
            mobileCatMenu.classList.toggle('hidden');
            if (mobileCatChevron) {
                mobileCatChevron.style.transform = mobileCatMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    }

    // Loading Screen helper
    function removeLoader() {
        const loader = document.getElementById('loading-screen');
        if (loader && loader.style.display !== 'none' && !loader.classList.contains('hidden')) {
            loader.classList.add('hidden');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    }

    // Loading Screen on Load
    window.addEventListener('load', function() {
        setTimeout(() => {
            removeLoader();
        }, 4000);
    });

    // Fallback: Remove loader after 7 seconds if window.load fails to fire
    setTimeout(() => {
        removeLoader();
    }, 7000);

    // Generate unique Tab ID
    window.TAB_ID = Math.random().toString(36).substr(2, 9);

    // Broadcast Channel for cart synchronization
    const cartChannel = new BroadcastChannel('cart_updates');

    // Cart Count Update Logic
    function updateCartCount(count, fromBroadcast = false) {
        // Update UI
        document.querySelectorAll('.cart-badge').forEach(badge => {
            if (badge) {
                badge.textContent = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        });

        // Broadcast to other tabs if this update didn't come from a broadcast
        if (!fromBroadcast) {
            cartChannel.postMessage({
                type: 'cart_updated',
                count: count,
                sourceTabId: window.TAB_ID
            });
        }
    }

    // Listen for cart updates from other tabs
    cartChannel.onmessage = (event) => {
        if (event.data.type === 'cart_updated') {
            updateCartCount(event.data.count, true);
        }
    };

    // Wishlist Count Update Logic
    function updateWishlistCount(count) {
        document.querySelectorAll('.wishlist-badge').forEach(badge => {
            if (badge) {
                badge.textContent = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        });
    }

    // Toast Notification System
    function showToast(message, type = 'success', subtitle = null) {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-24 right-5 z-[100] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-white border-primary' : 'bg-white border-red-500';
        const iconColor = type === 'success' ? 'text-primary' : 'text-red-500';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const subTextMessage = subtitle || (type === 'success' ? 'Successfully updated' : 'Operation failed');

        toast.className = `transform translate-x-[150%] transition-transform duration-500 ease-out pointer-events-auto shadow-2xl p-4 rounded-xl flex items-center gap-4 min-w-[300px] border-l-4 ${bgColor}`;
        toast.innerHTML = `
            <div class="w-10 h-10 ${type === 'success' ? 'bg-primary/10' : 'bg-red-50'} rounded-full flex items-center justify-center ${iconColor}">
                <i class="fas ${icon}"></i>
            </div>
            <div>
                <p class="font-bold text-dark">${message}</p>
                <p class="text-xs text-gray-500">${subTextMessage}</p>
            </div>
        `;

        toastContainer.appendChild(toast);

        // Animate in
        setTimeout(() => toast.classList.remove('translate-x-[150%]'), 100);

        // Animate out and remove
        setTimeout(() => {
            toast.classList.add('translate-x-[150%]');
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }
    
    // Broadcast Channel for cross-tab synchronization
    const wishlistChannel = new BroadcastChannel('wishlist_updates');

    window.updateWishlistCount = function(count) {
        const desktopBadge = document.querySelector('#wishlist-count-badge');
        const mobileBadge = document.querySelector('#mobile-wishlist-count-badge');
        
        if (desktopBadge) {
            desktopBadge.textContent = count;
            desktopBadge.classList.toggle('hidden', count === 0);
        }
        if (mobileBadge) {
            mobileBadge.textContent = count;
            mobileBadge.classList.toggle('hidden', count === 0);
        }
    };

    // Listen for updates from other tabs
    wishlistChannel.onmessage = (event) => {
        const { type, variantId, count, status } = event.data;
        if (type === 'wishlist_updated') {
            if (count !== undefined) updateWishlistCount(count);
            if (variantId !== undefined) syncWishlistIconState(variantId, status);
        }
    };

    function syncWishlistIconState(variantId, status) {
        const buttons = document.querySelectorAll(`[data-wishlist-variant-id="${variantId}"]`);
        buttons.forEach(btn => {
            const icon = btn.querySelector('i');
            const textSpan = btn.querySelector('.wishlist-text');
            
            if (icon) {
                if (status === 'added') {
                    icon.className = 'fas fa-heart text-red-500';
                    if (textSpan) textSpan.textContent = 'Remove from Wishlist';
                } else {
                    icon.className = 'far fa-heart';
                    icon.classList.remove('text-red-500');
                    if (textSpan) textSpan.textContent = 'Add to Wishlist';
                }
            }
        });
    }

    // Global Wishlist Addition Function
    window.addToWishlist = async function(variantId, btn = null) {
        @guest
            showToast('Please login for this feature', 'error', 'Login Required');
            return;
        @endguest

        if (!variantId) {
            showToast('Please select all options', 'error');
            return;
        }

        let icon = btn ? btn.querySelector('i') : null;
        let originalIconClass = icon ? icon.className : '';
        
        if (btn && icon) {
            btn.disabled = true;
            icon.className = 'fas fa-spinner fa-spin text-xs';
        }

        try {
            const response = await axios.post('{{ route('customer.wishlist.toggle') }}', {
                product_variant_id: variantId
            });

            if (response.data.success) {
                const status = response.data.status;
                showToast(response.data.message || 'Updated wishlist!', 'success');
                
                // Update locally
                if (typeof updateWishlistCount === 'function') {
                    updateWishlistCount(response.data.count);
                }
                syncWishlistIconState(variantId, status);

                // Broadcast to other tabs
                wishlistChannel.postMessage({
                    type: 'wishlist_updated',
                    variantId: variantId,
                    count: response.data.count,
                    status: status
                });
            }
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to update wishlist';
            if (error.response && error.response.status === 401) {
                showToast('Please login to update wishlist', 'error');
                setTimeout(() => window.location.href = '{{ route('customer.login') }}', 1500);
            } else {
                showToast(message, 'error');
            }
            if (icon) {
                icon.className = originalIconClass;
            }
        } finally {
            if (btn) {
                btn.disabled = false;
            }
        }
    };

    // Force refresh cart count on EVERY page show to ensure accuracy
    // This handles Back/Forward Cache (BFCache) and normal navigation
    window.addEventListener('pageshow', function(event) {
        refreshCartCount();
    });

    async function refreshCartCount() {
        try {
            // Add timestamp to prevent browser caching of the API response
            const response = await axios.get('{{ route('customer.cart.count') }}?t=' + new Date().getTime());
            if (response.data.success) {
                updateCartCount(response.data.count, true); // true = treat as local (don't re-broadcast)
            }
        } catch (error) {
            console.error('Failed to refresh cart count', error);
        }
    }
</script>
