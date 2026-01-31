<!-- Loading Screen -->
<div id="loading-screen">
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
            height: 120px;
            margin-bottom: 20px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.2);
        }

        .loading-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #92400e;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .loading-tagline {
            color: #b45309;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #f3f4f6;
            border-top: 3px solid #d97706;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <img src="{{ asset('images/logo.png') }}" alt="APIQO Jewellery" class="loading-logo">
    <div class="loading-name">APIQO Jewellery</div>
    <div class="loading-tagline">Luxury Redefined</div>
    <div class="loading-spinner"></div>
</div>

<!-- Header -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-amber-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between py-4">

            <!-- Logo -->
            <a href="{{ route('customer.home.index') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="APIQO Jewellery" class="h-10 w-10 rounded-full mr-3">
                <span class="text-2xl font-bold text-amber-800">APIQO</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('customer.home.index') }}" class="nav-link {{ request()->routeIs('customer.home.index') ? 'active' : '' }}">Home</a>
                <a href="{{ route('customer.products.list') }}" class="nav-link {{ request()->routeIs('customer.products.list') ? 'active' : '' }}">All Categories</a>
                @foreach($navCategories as $category)
                    <a href="{{ route('customer.category.products', $category->slug) }}" class="nav-link {{ request()->routeIs('customer.category.products') && request('slug') == $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
                @endforeach
            </nav>

            <!-- Right Section -->
            <div class="flex items-center gap-6">


                <a href="{{ route('customer.cart') }}" class="relative icon-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge hidden">0</span>
                </a>


                <!-- User -->
                @auth('customer')
                    <div class="relative group">
                        <button class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                <a href="{{ route('customer.account.profile') }}">
                                    <i class="fas fa-user text-amber-600"></i>

                                </a>
                            </div>
                            <span class="hidden md:inline">
                                {{ auth('customer')->user()->name }}
                            </span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Dropdown -->
                        <div class="absolute right-0 top-full pt-2 w-56 hidden group-hover:block z-50">
                            <div class="bg-white rounded-xl shadow-xl border border-amber-100 overflow-hidden">
                                <div class="p-4 border-b bg-gradient-to-r from-amber-50 to-white">
                                    <p class="font-bold text-amber-900 truncate">{{ auth('customer')->user()->name }}</p>
                                    <p class="text-xs text-amber-700/70 truncate">{{ auth('customer')->user()->email }}</p>
                                </div>
                                <div class="p-2 space-y-1">
                                    <a href="{{ route('customer.account.profile') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition-colors">
                                        <i class="fas fa-user-circle w-6 text-center text-amber-500/80"></i>
                                        My Account
                                    </a>
                                    <a href="{{ route('customer.account.orders') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition-colors">
                                        <i class="fas fa-shopping-bag w-6 text-center text-amber-500/80"></i>
                                        My Orders
                                    </a>
                                    <a href="{{ route('customer.wishlist.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition-colors">
                                        <i class="fas fa-heart w-6 text-center text-amber-500/80"></i>
                                        Wishlist
                                    </a>

                                    <div class="h-px bg-amber-50 my-1"></div>

                                    <form method="POST" action="{{ route('customer.logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors text-left">
                                            <i class="fas fa-sign-out-alt w-6 text-center text-red-400"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="icon-btn flex gap-2">
                        <i class="fas fa-user"></i>
                        <span class="hidden md:inline">Sign In</span>
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button id="mobileMenuToggle" class="md:hidden icon-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 pt-4 space-y-2">
            <!-- Home -->
            <a href="{{ route('customer.home.index') }}" class="mobile-link">
                <i class="fas fa-home mr-3 text-amber-600 w-5"></i>
                Home
            </a>

            <!-- All Categories -->
            <a href="{{ route('customer.products.list') }}" class="mobile-link">
                <i class="fas fa-th-large mr-3 text-amber-600 w-5"></i>
                All Categories
            </a>

            <!-- Categories Section Header -->
            <div class="pt-2 pb-1">
                <span class="text-xs font-semibold text-gray-500 uppercase px-4">Categories</span>
            </div>

            <!-- Categories -->
            <!-- Categories -->
            @foreach($navCategories as $category)
                <a href="{{ route('customer.category.products', $category->slug) }}" class="mobile-link">
                    <i class="fas fa-gem mr-3 text-amber-600 w-5"></i>
                    {{ $category->name }}
                </a>
            @endforeach

            <!-- Account Section Header -->
            <div class="pt-2 pb-1 border-t border-gray-100 mt-2">
                <span class="text-xs font-semibold text-gray-500 uppercase px-4">Account</span>
            </div>

            <!-- Cart -->
            <a href="{{ route('customer.cart') }}" class="mobile-link">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart mr-3 text-amber-600 w-5"></i>
                        Cart
                    </div> 
                    <a href="{{ route('customer.cart') }}" class="relative icon-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge hidden">0</span>
                    </a>

                </div>
            </a>

            @auth('customer')
                <!-- My Account -->
                <a href="{{ route('customer.account.profile') }}" class="mobile-link">
                    <i class="fas fa-user-circle mr-3 text-amber-600 w-5"></i>
                    My Account
                </a>

                <!-- My Orders -->
                <a href="{{ route('customer.account.orders') }}" class="mobile-link">
                    <i class="fas fa-clipboard-list mr-3 text-amber-600 w-5"></i>
                    My Orders
                </a>

                <!-- Wishlist -->
                <a href="{{ route('customer.wishlist.index') }}" class="mobile-link">
                    <i class="fas fa-heart mr-3 text-amber-600 w-5"></i>
                    Wishlist
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('customer.logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="mobile-link text-red-600 w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3 w-5"></i>
                        Logout
                    </button>
                </form>
            @else
                <!-- Login/Register -->
                <a href="{{ route('customer.login') }}" class="mobile-link">
                    <i class="fas fa-sign-in-alt mr-3 text-amber-600 w-5"></i>
                    Login / Register
                </a>
            @endauth

            <!-- Contact Section -->
            <div class="pt-2 pb-1 border-t border-gray-100 mt-2">
                <span class="text-xs font-semibold text-gray-500 uppercase px-4">Contact</span>
            </div>

            <!-- Contact Info -->
            <div class="px-4 py-3">
                <a href="tel:+911234567890" class="text-sm text-gray-600 hover:text-amber-700 block mb-2">
                    <i class="fas fa-phone mr-2 text-amber-600"></i>
                    +91 1234567890
                </a>
                <a href="mailto:info@apiqo.com" class="text-sm text-gray-600 hover:text-amber-700 block">
                    <i class="fas fa-envelope mr-2 text-amber-600"></i>
                    info@apiqo.com
                </a>
            </div>
        </div>

        <!-- Add some CSS for mobile links -->
        <style>
            .nav-link {
                color: #4b5563; /* text-gray-600 */
                font-weight: 500;
                transition: color 0.2s;
                position: relative;
            }

            .nav-link:hover {
                color: #92400e; /* text-amber-800 */
            }

            .nav-link.active {
                color: #92400e; /* text-amber-800 */
                font-weight: 700;
            }

            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 0;
                width: 100%;
                height: 2px;
                background-color: #d97706; /* amber-600 */
                border-radius: 99px;
            }

            .mobile-link {
                display: block;
                padding: 12px 16px;
                color: #374151;
                transition: all 0.2s ease;
                border-radius: 8px;
                margin: 2px 8px;
            }

            .mobile-link:hover {
                background-color: #FEF3C7;
                color: #92400e;
            }

            .cart-badge {
                background-color: #ef4444;
                color: white;
                font-size: 0.75rem;
                padding: 2px 6px;
                border-radius: 9999px;
            }
        </style>
    </div>
</header>



