<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - eCommerce Admin Panel</title>
    <link rel="icon" href="{{ \App\Helpers\SettingsHelper::get('favicon_url', asset('favicon.ico')) }}">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ \App\Helpers\SettingsHelper::get('theme_color', '#c98f83') }}',
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            /* Fallback */
            background-color: #f3f4f6; 
            /* Dynamic Primary Color Background with overlay */
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.9)), 
                        linear-gradient(135deg, {{ \App\Helpers\SettingsHelper::get('theme_color', '#c98f83') }} 0%, #333 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .btn-primary {
            background-color: {{ \App\Helpers\SettingsHelper::get('theme_color', '#c98f83') }};
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            filter: brightness(90%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="glass rounded-2xl shadow-2xl overflow-hidden max-w-md w-full">
        <!-- Logo Header -->
        <div class="p-8 text-center border-b border-gray-100">
            @if($logo = \App\Helpers\SettingsHelper::get('logo_url'))
                <img src="{{ $logo }}" alt="Logo" class="h-24 w-auto mx-auto mb-4 object-contain">
            @else
                <div
                    class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-store text-primary text-3xl"></i>
                </div>
            @endif
            <h1
                class="text-3xl font-bold text-gray-800 mb-2 font-playfair">
                {{ \App\Helpers\SettingsHelper::get('store_name', 'eCommerce') }}
            </h1>
            <p class="text-gray-500">Sign in to your admin dashboard</p>
        </div>

        <!-- Login Form -->
        <div class="p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <span class="text-red-700 font-medium">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Email
                    </label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-1  focus:border-transparent"
                        placeholder="Enter your email" autocomplete="email" autofocus value="{{ old('email') }}">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-1  focus:border-transparent"
                        placeholder="Enter your password" autocomplete="current-password">
                </div>

                {{-- <div class="flex items-center justify-between mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-5 h-5 border-2 border-gray-300 rounded checked:bg-indigo-500 checked:border-indigo-500 mr-2"
                               id="remember">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                </div> --}}

                <button type="submit" class="btn-primary w-full py-3 text-lg font-medium">
                    Sign In
                </button>
            </form>


        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} eCommerce Admin Panel
            </p>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
</body>

</html>
