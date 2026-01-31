@php
    $currentRoute = request()->route()->getName();
    $activeClass = 'text-primary after:w-full';
    $inactiveClass = 'hover:text-primary after:w-0 hover:after:w-full';
    
    // Fallback for when navCategories variable is missing (to prevent errors during migration)
    if (!isset($navCategories)) {
        $navCategories = \App\Models\Category::where('status', 1)->take(5)->get();
    }
@endphp

<!-- Loading Screen -->
<div id="loading-screen" style="display: none;">
    <style>
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        #loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .loading-logo {
            width: 120px;
            margin-bottom: 2rem;
            animation: pulse-logo 2s ease-in-out infinite;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f4f6;
            border-top: 3px solid #c98f83;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes pulse-logo {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <div class="flex flex-col items-center">
        @if(file_exists(public_path('storage/assets/images/logo.png')))
            <img src="{{ asset('storage/assets/images/logo.png') }}" alt="Logo" class="loading-logo">
        @else
            <h2 class="text-3xl font-playfair font-bold text-primary mb-8 loading-logo">{{ config('constants.SITE_NAME') }}</h2>
        @endif
        <div class="loading-spinner"></div>
    </div>
</div>

<script>
    (function() {
        // Only show loader if on home page AND it's the first visit of the session
        const isHomePage = window.location.pathname === '/' || window.location.pathname.endsWith('/index.php');
        const hasVisited = sessionStorage.getItem('hasVisited');
        const loader = document.getElementById('loading-screen');

        if (isHomePage && !hasVisited) {
            loader.style.display = 'flex';
            sessionStorage.setItem('hasVisited', 'true');
        }

        // Also show loader when any Home link is clicked
        document.addEventListener('click', function(e) {
            const homeLink = e.target.closest('a[href="{{ route('customer.home.index') }}"]');
            if (homeLink && !e.ctrlKey && !e.shiftKey && !e.metaKey) {
                loader.style.display = 'flex';
                loader.classList.remove('hidden');
            }
        });
    })();
</script>

<nav class="bg-white shadow-md sticky top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('customer.home.index') }}" class="text-2xl font-bold text-dark font-playfair flex items-center gap-2">
            @if(file_exists(public_path('storage/assets/images/logo.png')))
                <img src="{{ asset('storage/assets/images/logo.png') }}" alt="Logo" class="h-12">
            @else
                <span>{{ config('constants.SITE_NAME') }}</span>
            @endif
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center">
            <a href="{{ route('customer.home.index') }}"
                class="relative text-dark transition duration-300 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:bg-primary after:transition-all after:duration-300
                {{ $currentRoute === 'customer.home.index' ? $activeClass : $inactiveClass }}">
                Home
            </a>
            
            <!-- Category Dropdown -->
            <div class="relative group">
                <button class="flex items-center text-dark transition duration-300 hover:text-primary">
                    Categories
                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                </button>
                
                <div class="absolute left-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform translate-y-2 group-hover:translate-y-0">
                    <a href="{{ route('customer.products.list') }}" 
                        class="block px-4 py-3 text-dark hover:bg-primary/10 hover:text-primary transition duration-300 border-b border-gray-100 last:border-b-0">
                        All Categories
                    </a>
                    @foreach($navCategories as $cat)
                    <a href="{{ route('customer.category.products', $cat->slug) }}" 
                        class="block px-4 py-3 text-dark hover:bg-primary/10 hover:text-primary transition duration-300 border-b border-gray-100 last:border-b-0">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            
            <a href="{{ route('customer.products.list') }}"
                class="relative text-dark transition duration-300 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:bg-primary after:transition-all after:duration-300
                {{ $currentRoute === 'customer.products.list' ? $activeClass : $inactiveClass }}">
                Shop
            </a>

            <a href="{{ route('customer.products.rugs') }}"
                class="relative text-dark transition duration-300 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:bg-primary after:transition-all after:duration-300
                {{ $currentRoute === 'customer.products.rugs' ? $activeClass : $inactiveClass }}">
                Rugs
            </a>
            
            
            <!-- User Actions -->
            <div class="flex items-center gap-4 border-l pl-4 ml-4 border-gray-200">

                 <!-- Wishlist -->
                <a href="{{ route('customer.wishlist.index') }}" class="text-dark hover:text-primary transition relative">
                    <i class="fas fa-heart"></i>
                    <span id="wishlist-count-badge" class="wishlist-badge absolute -top-2 -right-2 bg-primary text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center {{ ($wishlistCount ?? 0) > 0 ? '' : 'hidden' }}">
                        {{ $wishlistCount ?? 0 }}
                    </span>
                </a>

                <!-- Cart -->
                <a href="{{ route('customer.cart') }}" class="relative text-dark hover:text-primary transition">
                    <i class="fas fa-cart-shopping text-xl"></i>
                    <span class="cart-badge absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>

                <!-- User Account -->
                @auth('customer')
                <div class="relative group">
                    <button class="flex items-center gap-2 text-dark hover:text-primary transition">
                        <i class="fas fa-user-circle text-xl"></i>
                    </button>
                    <!-- Dropdown -->
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform translate-y-2 group-hover:translate-y-0">
                        <div class="px-4 py-3 border-b border-gray-100">
                             <p class="text-sm font-semibold text-dark truncate">{{ auth('customer')->user()->name }}</p>
                        </div>
                        <a href="{{ route('customer.account.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary">Profile</a>
                        <a href="{{ route('customer.account.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary">Orders</a>
                        <a href="{{ route('customer.wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary">Wishlist</a>
                        <form method="POST" action="{{ route('customer.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('customer.login') }}" class="text-dark hover:text-primary transition font-medium text-sm">
                    Login
                </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="md:hidden flex items-center gap-4">
             <!-- Wishlist Mobile -->
             <a href="{{ route('customer.wishlist.index') }}" class="relative text-dark hover:text-primary transition">
                <i class="fas fa-heart text-xl"></i>
                <span id="mobile-wishlist-count-badge" class="wishlist-badge absolute -top-2 -right-2 bg-primary text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center {{ ($wishlistCount ?? 0) > 0 ? '' : 'hidden' }}">
                    {{ $wishlistCount ?? 0 }}
                </span>
            </a>

            <!-- Cart Mobile -->
             <a href="{{ route('customer.cart') }}" class="relative text-dark hover:text-primary transition">
                <i class="fas fa-shopping-bag text-xl"></i>
                <span class="cart-badge absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}">
                    {{ $cartCount ?? 0 }}
                </span>
            </a>
            <button id="mobile-menu-button" class="text-dark focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white px-4 py-2 shadow-lg space-y-2">
        <a href="{{ route('customer.home.index') }}" 
            class="block py-2 text-dark hover:text-primary font-medium">
            Home
        </a>

        <a href="{{ route('customer.products.list') }}" 
            class="block py-2 text-dark hover:text-primary font-medium">
            Shop
        </a>
        
        <div class="border-t border-gray-100 pt-2">
            <button id="mobile-categories-button" class="flex items-center justify-between w-full py-2 text-dark hover:text-primary font-medium focus:outline-none">
                <span>Categories</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-300" id="mobile-categories-chevron"></i>
            </button>
            <div id="mobile-categories-menu" class="hidden overflow-hidden transition-all duration-300">
                <a href="{{ route('customer.products.list') }}" class="block py-2 pl-4 text-dark hover:text-primary text-sm">
                    All Categories
                </a>
                @foreach($navCategories as $cat)
                <a href="{{ route('customer.category.products', $cat->slug) }}" class="block py-2 pl-4 text-dark hover:text-primary text-sm">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
        
        <div class="border-t border-gray-100 pt-2">
             @auth('customer')
                <a href="{{ route('customer.account.profile') }}" class="block py-2 text-dark hover:text-primary">My Account</a>
                <a href="{{ route('customer.account.orders') }}" class="block py-2 text-dark hover:text-primary">My Orders</a>
                <a href="{{ route('customer.wishlist.index') }}" class="block py-2 text-dark hover:text-primary">My Wishlist</a>
                <a href="{{ route('customer.account.change-password') }}" class="block py-2 text-dark hover:text-primary">Change Password</a>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-red-600">Logout</button>
                </form>
             @else
                <a href="{{ route('customer.login') }}" class="block py-2 text-primary font-medium">Login / Register</a>
             @endauth
        </div>
    </div>
</nav>