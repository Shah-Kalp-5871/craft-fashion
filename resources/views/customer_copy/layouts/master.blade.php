<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'APIQO Jewellery - Luxury Jewelry Store')</title>

    <meta name="description" content="@yield('meta_description', 'Discover exquisite handcrafted jewelry collections at APIQO Jewellery.')">
    <meta name="keywords" content="@yield('meta_keywords', 'jewelry, luxury jewelry, earrings, necklaces, rings, bracelets')">
    <meta name="author" content="APIQO Jewellery">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="@yield('og_title', 'APIQO Jewellery')">
    <meta property="og:description" content="@yield('og_description', 'Luxury Jewelry Store')">
    <meta property="og:image" content="@yield('og_image', asset('logo.jpeg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="APIQO Jewellery">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'APIQO Jewellery')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Luxury Jewelry Store')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('logo.jpeg'))">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- TailwindCSS - Compiled CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')

    <style>
        .brand-title { font-family: 'Playfair Display', serif; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-white text-gray-800">

    @include('customer.partials.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('customer.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.13.2/dist/axios.min.js"></script>

    @include('customer.partials.scripts')

    @stack('scripts')

    <script>
        function updateCartCount(count) {
            document.querySelectorAll('.cart-badge').forEach(badge => {
                badge.textContent = count;
                badge.classList.toggle('hidden', count <= 0);
            });
        }
    </script>

    @include('customer.partials.login-modal')
</body>
</html>
