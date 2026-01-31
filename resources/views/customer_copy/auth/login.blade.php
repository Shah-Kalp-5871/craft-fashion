@extends('customer.layouts.minimal')

@section('title', 'Login - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-amber-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="{{ route('customer.home.index') }}" class="inline-block">
                    <h1 class="text-4xl font-bold text-amber-800">{{ config('app.name') }}</h1>
                </a>
                <p class="text-gray-600 mt-2">Sign in to your account</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-600 text-sm">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ session('success') }}
                        </p>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h3 class="font-semibold text-red-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Please fix the following errors:
                        </h3>
                        <ul class="text-sm text-red-600">
                            @foreach($errors->all() as $error)
                                <li class="mb-1 flex items-start">
                                    <i class="fas fa-circle text-xs mt-1 mr-2"></i>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.login.submit') }}" id="loginForm" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors"
                            placeholder="user@example.com"
                            autocomplete="email">
                        @if($errors->has('email'))
                            <p class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors pr-12"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                id="passwordInput">
                            <button type="button" onclick="togglePassword('passwordInput')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-amber-600 transition-colors"
                                aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        @if($errors->has('password'))
                            <p class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2 text-amber-600 focus:ring-amber-200 focus:ring-2 rounded" {{ old('remember') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>

                        <a href="{{ route('customer.forgot-password') }}"
                            class="text-sm text-amber-600 hover:text-amber-800 transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        Sign In
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">Don't have an account?
                        <a href="{{ route('customer.register') }}" class="text-amber-600 hover:text-amber-800 font-medium transition-colors">
                            Register here
                        </a>
                    </p>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800 transition-colors">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to Home
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById('passwordToggleIcon');

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
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.getElementById('passwordInput');
    let isValid = true;

    // Clear previous error highlights
    emailInput.classList.remove('border-red-500');
    passwordInput.classList.remove('border-red-500');

    // Validate email
    if (!emailInput.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        emailInput.classList.add('border-red-500');
        isValid = false;
    }

    // Validate password
    if (!passwordInput.value || passwordInput.value.length < 8) {
        passwordInput.classList.add('border-red-500');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        if (!emailInput.value) {
            showToast('Please enter your email address', 'error');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
            showToast('Please enter a valid email address', 'error');
        } else if (!passwordInput.value) {
            showToast('Please enter your password', 'error');
        } else if (passwordInput.value.length < 8) {
            showToast('Password must be at least 8 characters', 'error');
        }
    }
});

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-amber-100 text-amber-800 border border-amber-200'}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
    }, 10);

    // Remove after 5 seconds
    setTimeout(() => {
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 5000);
}
</script>
@endsection
