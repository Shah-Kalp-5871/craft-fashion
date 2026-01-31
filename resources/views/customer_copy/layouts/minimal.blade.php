<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Checkout') - APIQO Jewellery</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .brand-title {
            font-family: 'Playfair Display', serif;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Minimal Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('customer.home.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="APIQO Jewellery" class="h-10 w-10 rounded-full">
                    <span class="brand-title text-xl text-amber-800 font-bold">APIQO Jewellery</span>
                </a>
                
                <!-- Progress Steps -->
                @hasSection('checkout_progress')
                <div class="hidden md:flex items-center gap-4">
                    @yield('checkout_progress')
                </div>
                @endif
                
                <!-- Support -->
                <div class="flex items-center gap-4">
                    <a href="tel:+911800124567" class="text-gray-600 hover:text-amber-700 text-sm">
                        <i class="fas fa-phone-alt mr-1"></i>
                        +91  7490003767
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Minimal Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} APIQO Jewellery. All rights reserved.</p>
                <div class="flex gap-6 mt-2 md:mt-0">
                    <a href="{{ route('customer.page.privacy') }}" class="hover:text-amber-700">Privacy Policy</a>
                    <a href="{{ route('customer.page.terms') }}" class="hover:text-amber-700">Terms & Conditions</a>
                    <a href="{{ route('customer.page.contact') }}" class="hover:text-amber-700">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    @yield('scripts')
</body>
</html>