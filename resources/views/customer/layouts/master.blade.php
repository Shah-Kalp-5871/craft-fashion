<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('constants.SITE_NAME'))</title>
    <meta name="description" content="@yield('description', 'Craft Fashion offers exquisite kurtis, tops, kurti-bottom sets, and boutique garments for women, girls, and kids in Yamuna Nagar.')">
    <meta name="keywords" content="kurti, women's clothing, kids fashion, boutique garments, traditional wear, Craft Fashion Yamuna Nagar">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('constants.SITE_NAME'))">
    <meta property="og:description" content="@yield('description', 'Exquisite kurtis, tops, and boutique garments for women, girls, and kids in Yamuna Nagar.')">
    <meta property="og:image" content="@yield('og_image', asset('storage/assets/images/logo.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('constants.SITE_NAME'))">
    <meta property="twitter:description" content="@yield('description', 'Exquisite kurtis, tops, and boutique garments for women, girls, and kids in Yamuna Nagar.')">
    <meta property="twitter:image" content="@yield('og_image', asset('storage/assets/images/logo.jpg'))">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.jpg') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#c98f83',
                        secondary: '#747471',
                        dark: '#393333',
                    },
                    fontFamily: {
                        'playfair': ['"Playfair Display"', 'serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Swiper JS -->
    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    @yield('styles')
    @stack('styles')

    <style>
        .product-card:hover .product-overlay {
            opacity: 1;
        }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="font-poppins bg-gray-50 text-gray-800">

    @include('customer.partials.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('customer.partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.13.2/dist/axios.min.js"></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Anime.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <!-- Swiper -->
    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Typed.js -->
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('customer.partials.scripts')

    @stack('scripts')

    <!-- Floating WhatsApp Button -->
    <a href="{{ config('constants.WHATSAPP_LINK') }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-2xl transition-all duration-300 transform hover:scale-110 group"
       aria-label="Contact us on WhatsApp">
        <i class="fab fa-whatsapp text-3xl"></i>
        <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
            Chat with us!
        </span>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
    </a>

    @include('customer.partials.login-modal')
</body>
</html>