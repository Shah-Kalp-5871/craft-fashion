<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') - APIQO Jewellery</title>
    
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
<body class="bg-gradient-to-br from-amber-50 to-beige-100 min-h-screen flex items-center justify-center p-4">
    
    <!-- Background decorative elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-64 h-64 bg-amber-200/20 rounded-full blur-3xl -translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-300/10 rounded-full blur-3xl translate-x-32 translate-y-32"></div>
    </div>
    
    <!-- Main content -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('customer.home.index') }}" class="inline-block">
                <img src="{{ asset('logo.jpeg') }}" alt="APIQO Jewellery" class="h-16 w-16 rounded-full mx-auto mb-4 shadow-lg">
                <h1 class="brand-title text-3xl text-amber-800 font-bold">APIQO Jewellery</h1>
                <p class="text-gray-600 text-sm mt-2">Luxury Redefined</p>
            </a>
        </div>
        
        <!-- Auth Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <!-- Back to Home -->
                <div class="mb-6">
                    <a href="{{ route('customer.home.index') }}" class="inline-flex items-center text-amber-700 hover:text-amber-800 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Home
                    </a>
                </div>
                
                <!-- Page Title -->
                <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">
                    @yield('auth_title')
                </h2>
                <p class="text-gray-600 text-center mb-8">
                    @yield('auth_subtitle')
                </p>
                
                <!-- Content -->
                @yield('auth_content')
            </div>
        </div>
        
        <!-- Footer Links -->
        <div class="mt-6 text-center text-gray-600 text-sm">
            @yield('auth_footer')
        </div>
    </div>
    
    <!-- Scripts -->
    @yield('scripts')
</body>
</html>