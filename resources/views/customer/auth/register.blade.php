@extends('customer.layouts.master')

@section('title', config('app.name') . ' | Register')

@push('styles')
<style>
    /* Password strength indicator */
    .password-strength {
        height: 4px;
        transition: all 0.3s ease;
    }
    
    .strength-weak { width: 25%; background-color: #EF4444; }
    .strength-fair { width: 50%; background-color: #F59E0B; }
    .strength-good { width: 75%; background-color: #10B981; }
    .strength-strong { width: 100%; background-color: #10B981; }
    
    .password-requirements {
        font-size: 12px;
        color: #6B7280;
    }
    
    .requirement-met {
        color: #10B981;
    }
    
    .requirement-unmet {
        color: #9CA3AF;
    }
    
    /* Form validation styling */
    .form-success {
        border-color: #10B981 !important;
        background-color: #F0FDF4 !important;
    }
    
    .form-error {
        border-color: #EF4444 !important;
        background-color: #FEF2F2 !important;
    }
</style>
@endpush

@section('content')

<!-- Register Section with Hero Background -->
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
            <p class="text-secondary text-lg">Join our premium fashion community</p>
        </div>

        <div class="max-w-2xl mx-auto">
            <!-- Progress Steps -->
            <div class="mb-12">
                <div class="flex items-center justify-center">
                    <!-- Step 1 -->
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg shadow-lg">
                            1
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-dark">Create Account</p>
                            <p class="text-sm text-secondary">Basic information</p>
                        </div>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-24 h-1 bg-gray-200 mx-8 hidden md:block"></div>
                    
                    <!-- Step 2 -->
                    <div class="flex items-center opacity-50">
                        <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold text-lg">
                            2
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-400">Verify Account</p>
                            <p class="text-sm text-gray-400">Email verification</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-dark to-primary text-white p-8">
                    <h2 class="text-3xl font-playfair font-bold text-center">Create Account</h2>
                    <p class="text-white/80 text-center mt-2">Join thousands of satisfied customers</p>
                </div>

                <!-- Register Form -->
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

                    <form method="POST" action="{{ route('customer.register.submit') }}" id="registerForm">
                        @csrf
                        <input type="hidden" name="form" value="register">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name Input -->
                            <div>
                                <label for="name" class="block text-dark font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-primary"></i>Full Name
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                        placeholder="Enter your full name"
                                        required>
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="far fa-user"></i>
                                    </div>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Input -->
                            <div>
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
                                        required>
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="far fa-envelope"></i>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mobile Input -->
                            <div>
                                <label for="mobile" class="block text-dark font-medium mb-2">
                                    <i class="fas fa-phone mr-2 text-primary"></i>Mobile Number
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input 
                                        type="tel" 
                                        id="mobile" 
                                        name="mobile" 
                                        value="{{ old('mobile') }}"
                                        class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                        placeholder="Enter 10-digit mobile number"
                                        pattern="[0-9]{10}"
                                        maxlength="10"
                                        required>
                                </div>
                                @error('mobile')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div>
                                <label for="password" class="block text-dark font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-primary"></i>Password
                                </label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                        placeholder="Create a strong password"
                                        required>
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition">
                                        <i class="far fa-eye" id="toggleIconPassword"></i>
                                    </button>
                                </div>
                                

                                
                                <!-- Password Requirements -->

                                
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password Input -->
                            <div>
                                <label for="password_confirmation" class="block text-dark font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-primary"></i>Confirm Password
                                </label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                        placeholder="Confirm your password"
                                        required>
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition">
                                        <i class="far fa-eye" id="toggleIconConfirm"></i>
                                    </button>
                                </div>
                                <div class="mt-2 text-sm" id="passwordMatch"></div>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mt-8 mb-8">
                            <div class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    id="terms" 
                                    name="terms"
                                    class="h-5 w-5 text-primary rounded focus:ring-primary/20 border-gray-300 mt-1"
                                    required>
                                <label for="terms" class="ml-3 text-secondary">
                                    I agree to the 
                                    <a href="{{ route('customer.page.terms') }}" class="text-primary font-semibold hover:text-dark transition" target="_blank">
                                        Terms & Conditions
                                    </a> 
                                    and 
                                    <a href="{{ route('customer.page.privacy') }}" class="text-primary font-semibold hover:text-dark transition" target="_blank">
                                        Privacy Policy
                                    </a>
                                </label>
                            </div>
                            @error('terms')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-dark to-primary text-white py-4 rounded-xl font-semibold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
                            <span>Create Account</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>

                        <!-- Divider -->
                        {{-- <div class="flex items-center my-8">
                            <div class="flex-1 border-t border-gray-200"></div>
                            <span class="px-4 text-secondary text-sm">or sign up with</span>
                            <div class="flex-1 border-t border-gray-200"></div>
                        </div> --}}

                        <!-- Social Register (Optional) -->
                        {{-- <div class="grid grid-cols-2 gap-4 mb-8">
                            <a href="#" class="flex items-center justify-center p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition group">
                                <i class="fab fa-google text-red-500 mr-2"></i>
                                <span class="text-secondary group-hover:text-dark">Google</span>
                            </a>
                            <a href="#" class="flex items-center justify-center p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition group">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                <span class="text-secondary group-hover:text-dark">Facebook</span>
                            </a>
                        </div> --}}
                    </form>

                    <!-- Login Link -->
                    <div class="text-center pt-6 border-t border-gray-100">
                        <p class="text-secondary">
                            Already have an account?
                            <a href="{{ route('customer.login') }}" class="text-primary font-semibold hover:text-dark transition ml-1">
                                Sign In
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            {{-- <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gift text-primary text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-dark mb-2">Welcome Bonus</h4>
                    <p class="text-sm text-secondary">Get ₹100 off on your first order</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-primary text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-dark mb-2">Priority Support</h4>
                    <p class="text-sm text-secondary">Exclusive access to VIP customer service</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tags text-primary text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-dark mb-2">Early Access</h4>
                    <p class="text-sm text-secondary">Be the first to see new collections</p>
                </div>
            </div> --}}
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
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        let toggleIcon = document.getElementById(`toggleIcon${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)}`);
        
        if (!toggleIcon) {
            // Fallback for confirm password
            toggleIcon = document.getElementById(fieldId === 'password' ? 'toggleIconPassword' : 'toggleIconConfirm');
        }
        
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



    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchText = document.getElementById('passwordMatch');
        
        if (confirmPassword.length === 0) {
            matchText.textContent = '';
            matchText.className = 'mt-2 text-sm';
        } else if (password === confirmPassword) {
            matchText.textContent = '✓ Passwords match';
            matchText.className = 'mt-2 text-sm text-green-600';
        } else {
            matchText.textContent = '✗ Passwords do not match';
            matchText.className = 'mt-2 text-sm text-red-600';
        }
    }

    // Mobile number formatting
    document.getElementById('mobile').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) value = value.substring(0, 10);
        e.target.value = value;
    });

    // Name formatting
    document.getElementById('name').addEventListener('input', function(e) {
        // Allow only letters and spaces
        e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
    });

    // Real-time password validation
    // Real-time password validation
    document.getElementById('password').addEventListener('input', function(e) {
        checkPasswordMatch();
    });

    document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const mobile = document.getElementById('mobile').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms').checked;
        
        let isValid = true;
        let errorMessage = '';
        
        // Name validation
        if (!name || name.length < 2) {
            isValid = false;
            errorMessage = 'Please enter a valid name (minimum 2 characters)';
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        }
        
        // Mobile validation
        const mobileRegex = /^[0-9]{10}$/;
        if (!mobileRegex.test(mobile)) {
            isValid = false;
            errorMessage = 'Please enter a valid 10-digit mobile number';
        }
        
        // Password validation
        // Password validation (Simple length check only)
        if (password.length < 8) {
            isValid = false;
            errorMessage = 'Password must be at least 8 characters';
        }
        
        // Password match
        if (password !== confirmPassword) {
            isValid = false;
            errorMessage = 'Passwords do not match';
        }
        
        // Terms validation
        if (!terms) {
            isValid = false;
            errorMessage = 'You must accept the terms and conditions';
        }
        
        if (!isValid) {
            e.preventDefault();
            triggerToast(errorMessage, 'error');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span>Creating Account...</span><i class="fas fa-spinner fa-spin ml-2"></i>';
        submitBtn.disabled = true;
    });

    // Auto focus on first error field
    @if($errors->any())
        const firstErrorField = document.querySelector('.text-red-600')?.closest('div')?.querySelector('input');
        if (firstErrorField) {
            firstErrorField.focus();
        }
    @endif

    // Toast notification function (removed local duplicate to use global one from scripts.blade.php)
    function triggerToast(message, type = 'info') {
        if (typeof showToast === 'function') {
            showToast(message, type);
        } else {
            alert(message);
        }
    }

    // Initialize password strength on page load
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password').value;
        if (password) {

            checkPasswordMatch();
        }
    });

    // Reset button state when page is restored from bfcache
    window.addEventListener('pageshow', function(event) {
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<span>Create Account</span><i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>';
            submitBtn.disabled = false;
        }
    });
</script>
@endpush