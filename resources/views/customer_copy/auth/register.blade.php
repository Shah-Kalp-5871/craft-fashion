@extends('customer.layouts.minimal')

@section('title', 'Register - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-amber-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="{{ route('customer.home.index') }}" class="inline-block">
                    <h1 class="text-4xl font-bold text-amber-800">{{ config('app.name') }}</h1>
                </a>
                <p class="text-gray-600 mt-2">Create your account</p>
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

                <form method="POST" action="{{ route('customer.register.submit') }}" id="registerForm" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors"
                            placeholder="John Doe"
                            autocomplete="name">
                        <p class="text-xs text-gray-500 mt-1">Only letters and spaces allowed</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors"
                            placeholder="user@example.com"
                            autocomplete="email">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Mobile Number</label>
                        <input type="tel" name="mobile" value="{{ old('mobile') }}" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('mobile') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors"
                            placeholder="9876543210"
                            autocomplete="tel">
                        <p class="text-xs text-gray-500 mt-1">10-15 digits without spaces or special characters</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors pr-12"
                                placeholder="Enter your password"
                                autocomplete="new-password"
                                id="passwordInput">
                            <button type="button" onclick="togglePassword('passwordInput')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-amber-600 transition-colors"
                                aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="passwordInputToggleIcon"></i>
                            </button>
                        </div>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-1">Password must contain:</p>
                            <ul class="text-xs text-gray-600 space-y-1">
                                <li class="flex items-center" id="lengthCheck">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    At least 8 characters
                                </li>
                                <li class="flex items-center" id="uppercaseCheck">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    One uppercase letter (A-Z)
                                </li>
                                <li class="flex items-center" id="lowercaseCheck">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    One lowercase letter (a-z)
                                </li>
                                <li class="flex items-center" id="numberCheck">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    One number (0-9)
                                </li>
                                <li class="flex items-center" id="specialCheck">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    One special character (@$!%*?&)
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors pr-12"
                                placeholder="Confirm your password"
                                autocomplete="new-password"
                                id="confirmPasswordInput">
                            <button type="button" onclick="togglePassword('confirmPasswordInput')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-amber-600 transition-colors"
                                aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="confirmPasswordInputToggleIcon"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="hidden mt-1">
                            <p class="text-xs text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                Passwords match
                            </p>
                        </div>
                        <div id="passwordMismatch" class="hidden mt-1">
                            <p class="text-xs text-red-600">
                                <i class="fas fa-times-circle mr-1"></i>
                                Passwords do not match
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <input type="checkbox" name="terms" required
                               class="mt-1 mr-2 text-amber-600 focus:ring-amber-200 focus:ring-2 rounded"
                               {{ old('terms') ? 'checked' : '' }}
                               id="termsCheckbox">
                        <label for="termsCheckbox" class="text-sm text-gray-600 cursor-pointer">
                            I agree to the
                            <a href="{{ route('customer.page.terms') }}" class="text-amber-600 hover:text-amber-800 underline transition-colors">
                                Terms & Conditions
                            </a>
                            and
                            <a href="{{ route('customer.page.privacy') }}" class="text-amber-600 hover:text-amber-800 underline transition-colors">
                                Privacy Policy
                            </a>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submitBtn">
                        Create Account
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">Already have an account?
                        <a href="{{ route('customer.login') }}" class="text-amber-600 hover:text-amber-800 font-medium transition-colors">
                            Login here
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
    const toggleIcon = document.getElementById(inputId + 'ToggleIcon');

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

// Real-time password validation
const passwordInput = document.getElementById('passwordInput');
const confirmPasswordInput = document.getElementById('confirmPasswordInput');
const submitBtn = document.getElementById('submitBtn');

passwordInput.addEventListener('input', validatePassword);
confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);

function validatePassword() {
    const password = passwordInput.value;

    // Check length
    const lengthCheck = document.getElementById('lengthCheck');
    if (password.length >= 8) {
        lengthCheck.innerHTML = '<i class="fas fa-check text-green-500 text-xs mr-2"></i>At least 8 characters';
    } else {
        lengthCheck.innerHTML = '<i class="fas fa-circle text-xs mr-2"></i>At least 8 characters';
    }

    // Check uppercase
    const uppercaseCheck = document.getElementById('uppercaseCheck');
    if (/[A-Z]/.test(password)) {
        uppercaseCheck.innerHTML = '<i class="fas fa-check text-green-500 text-xs mr-2"></i>One uppercase letter (A-Z)';
    } else {
        uppercaseCheck.innerHTML = '<i class="fas fa-circle text-xs mr-2"></i>One uppercase letter (A-Z)';
    }

    // Check lowercase
    const lowercaseCheck = document.getElementById('lowercaseCheck');
    if (/[a-z]/.test(password)) {
        lowercaseCheck.innerHTML = '<i class="fas fa-check text-green-500 text-xs mr-2"></i>One lowercase letter (a-z)';
    } else {
        lowercaseCheck.innerHTML = '<i class="fas fa-circle text-xs mr-2"></i>One lowercase letter (a-z)';
    }

    // Check number
    const numberCheck = document.getElementById('numberCheck');
    if (/[0-9]/.test(password)) {
        numberCheck.innerHTML = '<i class="fas fa-check text-green-500 text-xs mr-2"></i>One number (0-9)';
    } else {
        numberCheck.innerHTML = '<i class="fas fa-circle text-xs mr-2"></i>One number (0-9)';
    }

    // Check special character
    const specialCheck = document.getElementById('specialCheck');
    if (/[@$!%*?&]/.test(password)) {
        specialCheck.innerHTML = '<i class="fas fa-check text-green-500 text-xs mr-2"></i>One special character (@$!%*?&)';
    } else {
        specialCheck.innerHTML = '<i class="fas fa-circle text-xs mr-2"></i>One special character (@$!%*?&)';
    }

    validatePasswordConfirmation();
}

function validatePasswordConfirmation() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const matchDiv = document.getElementById('passwordMatch');
    const mismatchDiv = document.getElementById('passwordMismatch');

    if (confirmPassword.length === 0) {
        matchDiv.classList.add('hidden');
        mismatchDiv.classList.add('hidden');
        return;
    }

    if (password === confirmPassword) {
        matchDiv.classList.remove('hidden');
        mismatchDiv.classList.add('hidden');
    } else {
        matchDiv.classList.add('hidden');
        mismatchDiv.classList.remove('hidden');
    }
}

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const mobileInput = document.querySelector('input[name="mobile"]');
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const termsCheckbox = document.getElementById('termsCheckbox');

    let isValid = true;
    const errors = [];

    // Validate name
    if (!nameInput.value || !/^[a-zA-Z\s]+$/.test(nameInput.value)) {
        nameInput.classList.add('border-red-500');
        errors.push('Please enter a valid name (letters and spaces only)');
        isValid = false;
    } else {
        nameInput.classList.remove('border-red-500');
    }

    // Validate email
    if (!emailInput.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        emailInput.classList.add('border-red-500');
        errors.push('Please enter a valid email address');
        isValid = false;
    } else {
        emailInput.classList.remove('border-red-500');
    }

    // Validate mobile
    if (!mobileInput.value || !/^[0-9]{10,15}$/.test(mobileInput.value)) {
        mobileInput.classList.add('border-red-500');
        errors.push('Please enter a valid 10-15 digit mobile number');
        isValid = false;
    } else {
        mobileInput.classList.remove('border-red-500');
    }

    // Validate password
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!password || !passwordRegex.test(password)) {
        passwordInput.classList.add('border-red-500');
        errors.push('Password must contain at least one uppercase, one lowercase, one number, and one special character');
        isValid = false;
    } else {
        passwordInput.classList.remove('border-red-500');
    }

    // Validate password confirmation
    if (password !== confirmPassword) {
        confirmPasswordInput.classList.add('border-red-500');
        errors.push('Passwords do not match');
        isValid = false;
    } else {
        confirmPasswordInput.classList.remove('border-red-500');
    }

    // Validate terms
    if (!termsCheckbox.checked) {
        errors.push('You must accept the terms and conditions');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        showErrorToast(errors[0]);
    }
});

function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 p-4 bg-red-100 text-red-800 border border-red-200 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
    }, 10);

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
