@extends('customer.layouts.master')

@section('title', config('app.name') . ' | Forgot Password')

@section('content')

<!-- Forgot Password Section with Hero Background -->
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
            <p class="text-secondary text-lg">Reset your secure access</p>
        </div>

        <div class="max-w-md mx-auto">
            <!-- Forgot Password Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-dark to-primary text-white p-8">
                    <h2 class="text-3xl font-playfair font-bold text-center">Forgot Password</h2>
                    <p class="text-white/80 text-center mt-2">Enter your email and we'll send you a reset link</p>
                </div>

                <!-- Form -->
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

                    <form method="POST" action="{{ route('customer.forgot-password.submit') }}" id="forgotPasswordForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-8">
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
                                    placeholder="Enter your registered email"
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

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-dark to-primary text-white py-4 rounded-xl font-semibold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group mb-6">
                            <span>Send Reset Link</span>
                            <i class="fas fa-paper-plane ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('customer.login') }}" class="text-secondary hover:text-primary transition font-medium">
                                <i class="fas fa-arrow-left mr-2 text-sm"></i>Back to Sign In
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Note -->
            <div class="mt-8 text-center">
                <p class="text-sm text-secondary">
                    Having trouble? 
                    <a href="{{ route('customer.page.contact') }}" class="text-primary font-semibold hover:underline">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Banner -->
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
    // Form validation
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
        // Since we don't have the processing route yet, we'll just show a message or let it fail if the user clicks.
        // But the user only asked for the page.
        const email = document.getElementById('email').value;
        
        if (!email) {
            e.preventDefault();
            showToast('Please enter your email address', 'error');
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showToast('Please enter a valid email address', 'error');
            return;
        }
    });

    // Toast notification function (matching login page)
    function showToast(message, type = 'info') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }

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

        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }
</script>

<style>
    .font-playfair {
        font-family: 'Playfair Display', serif;
    }
    
    input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.1);
    }
    
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
    }
</style>
@endpush
