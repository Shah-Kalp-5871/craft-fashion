@extends('customer.layouts.master')

@section('title', config('app.name') . ' | Login')

@section('content')

<!-- Login Section with Hero Background -->
<section class="relative bg-gradient-to-br from-purple-50 via-white to-pink-50 py-16 min-h-screen">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNlZGYyZjYiIGZpbGwtb3BhY2l0eT0iMC40Ij48Y2lyY2xlIGN4PSIzIiBjeT0iMyIgcj0iMyIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Logo / Brand Header -->
        <div class="text-center mb-12">
            <a href="{{ route('customer.home.index') }}" class="inline-block">
                <div class="text-4xl font-playfair font-bold text-primary mb-2">
                    <span class="text-dark">Craft</span> Fashion
                </div>
            </a>
            <p class="text-secondary text-lg">Welcome back to premium fashion</p>
        </div>

        <div class="max-w-md mx-auto">
            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-dark to-primary text-white p-8">
                    <h2 class="text-3xl font-playfair font-bold text-center">Sign In</h2>
                    <p class="text-white/80 text-center mt-2">Access your account to continue shopping</p>
                </div>

                <!-- Login Form -->
                <div class="p-8">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Success!</h3>
                                <div class="mt-1 text-sm text-green-700">
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Error!</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    {{ session('error') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.login.submit') }}" id="loginForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-6">
                            <label for="email" class="block text-dark font-medium mb-2">
                                <i class="fas fa-envelope mr-2 text-primary"></i>Email Address
                            </label>
                            <div class="relative">
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                    placeholder="Enter your email"
                                    required
                                    autofocus>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="far fa-envelope"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-dark font-medium">
                                    <i class="fas fa-lock mr-2 text-primary"></i>Password
                                </label>
                                <a href="{{ route('customer.forgot-password') }}" class="text-sm text-primary hover:text-dark transition">
                                    Forgot Password?
                                </a>
                            </div>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                    placeholder="Enter your password"
                                    required>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition">
                                    <i class="far fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Terms -->
                        <div class="mb-8">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    name="remember"
                                    class="h-5 w-5 text-primary rounded focus:ring-primary/20 border-gray-300">
                                <label for="remember" class="ml-3 text-secondary">
                                    Keep me signed in
                                </label>
                            </div>
                            @error('terms')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-dark to-primary text-white py-4 rounded-xl font-semibold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
                            <span>Sign In</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>

                        <!-- Divider -->
                        <!-- <div class="flex items-center my-8">
                            <div class="flex-1 border-t border-gray-200"></div>
                            <span class="px-4 text-secondary text-sm">or continue with</span>
                            <div class="flex-1 border-t border-gray-200"></div>
                        </div> -->

                        <!-- Social Login (Optional)
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <a href="#" class="flex items-center justify-center p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition group">
                                <i class="fab fa-google text-red-500 mr-2"></i>
                                <span class="text-secondary group-hover:text-dark">Google</span>
                            </a>
                            <a href="#" class="flex items-center justify-center p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition group">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                <span class="text-secondary group-hover:text-dark">Facebook</span>
                            </a>
                        </div> -->
                    </form>

                    <!-- Register Link -->
                    <div class="text-center pt-6 border-t border-gray-100">
                        <p class="text-secondary">
                            Don't have an account?
                            <a href="{{ route('customer.register') }}" class="text-primary font-semibold hover:text-dark transition ml-1">
                                Create Account
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security Note -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center text-sm text-secondary">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    <span>Your data is secured with 256-bit SSL encryption</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Banner (Consistent with Homepage) -->
<section class="py-12 bg-gradient-to-r from-primary to-primary/90 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Free Shipping</h3>
                <p class="text-white/80">On orders above ₹999</p>
            </div>

            <div class="p-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
                <p class="text-white/80">100% secure & encrypted</p>
            </div>

            <div class="p-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Easy Returns</h3>
                <p class="text-white/80">7-day return policy</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Simple validation
        if (!email || !password) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
            return;
        }
        
        // Email format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showToast('Please enter a valid email address', 'error');
            return;
        }
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                ${type === 'error' ? '!' : 'i'}
            </div>
            <div class="toast-message">${message}</div>
            <button onclick="this.parentElement.remove()" class="toast-close">
                &times;
            </button>
        `;

        // Add to document
        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    // Auto focus on email field if error exists
    @if($errors->has('email') || $errors->has('password'))
        document.getElementById('email').focus();
    @endif
</script>

<style>
    /* Custom styles matching your theme */
    .font-playfair {
        font-family: 'Playfair Display', serif;
    }
    
    /* Input focus effects */
    input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.1);
    }
    
    /* Checkbox styling */
    input[type="checkbox"]:checked {
        background-color: #9C27B0;
        border-color: #9C27B0;
    }
    
    /* Button hover effect */
    button[type="submit"]:hover {
        background-position: right center;
    }
    
    /* Social login hover effects */
    .social-login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Toast notification styles (reuse from homepage) */
    .toast-notification {
        position: fixed;
        top: 80px;
        right: 20px;
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-left: 4px solid #EC4899;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        z-index: 9999;
        min-width: 300px;
        max-width: 350px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.error {
        border-left-color: #EF4444;
    }

    .toast-notification.info {
        border-left-color: #3B82F6;
    }

    .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .toast-notification.error .toast-icon {
        background: #EF4444;
        color: white;
    }

    .toast-notification.info .toast-icon {
        background: #3B82F6;
        color: white;
    }

    .toast-message {
        flex: 1;
        font-weight: 500;
        color: #1F2937;
        font-size: 14px;
    }

    .toast-close {
        color: #9CA3AF;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
        transition: color 0.2s;
        padding: 4px;
        border-radius: 4px;
    }

    .toast-close:hover {
        color: #6B7280;
        background: #F3F4F6;
    }

    /* Mobile responsiveness */
    @media (max-width: 640px) {
        .toast-notification {
            top: auto;
            bottom: 20px;
            right: 20px;
            left: 20px;
            max-width: none;
            transform: translateY(120%);
        }
        
        .toast-notification.show {
            transform: translateY(0);
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>
@endpush