@extends('customer.layouts.master')

@section('title', 'Change Password - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('customer.account.profile') }}" class="text-amber-600 hover:text-amber-800">My Account</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600">Change Password</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-amber-700"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $customer->email ?? $customer->mobile }}</p>
                        @if($customer->password_changed_at)
                        <p class="text-xs text-gray-500 mt-1">
                            Last changed: {{ $customer->password_changed_at->diffForHumans() }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('customer.account.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('customer.wishlist.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-heart"></i>
                        <span>My Wishlist</span>
                        @php
                            $wishlistCount = \App\Models\Wishlist::where('customer_id', $customer->id)->count();
                        @endphp
                        @if($wishlistCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $wishlistCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.orders') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                        @php
                            $ordersCount = \App\Models\Order::where('customer_id', $customer->id)->count();
                        @endphp
                        @if($ordersCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $ordersCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.addresses') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Addresses</span>
                    </a>

                    <a href="{{ route('customer.account.change-password') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>

                    <form method="POST" action="{{ route('customer.logout') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 w-full">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h2>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="max-w-lg">
                    <form method="POST" action="{{ route('customer.account.change-password.update') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-gray-700 mb-2">Current Password *</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="currentPassword" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none pr-12">
                                <button type="button" onclick="togglePassword('currentPassword')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">New Password *</label>
                            <div class="relative">
                                <input type="password" name="password" id="newPassword" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none pr-12">
                                <button type="button" onclick="togglePassword('newPassword')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2 space-y-1">
                                <p class="text-xs text-gray-600">Password must contain:</p>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li id="lengthCheck" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                        At least 8 characters
                                    </li>
                                    <li id="uppercaseCheck" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                        At least one uppercase letter
                                    </li>
                                    <li id="lowercaseCheck" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                        At least one lowercase letter
                                    </li>
                                    <li id="numberCheck" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                        At least one number
                                    </li>
                                    <li id="specialCheck" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 text-xs mr-2"></i>
                                        At least one special character
                                    </li>
                                </ul>
                            </div>
                            @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Confirm New Password *</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirmPassword" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none pr-12">
                                <button type="button" onclick="togglePassword('confirmPassword')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p id="passwordMatchMessage" class="text-sm mt-2 hidden"></p>
                        </div>

                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('customer.account.profile') }}"
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-8 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 flex-1">
                                Update Password
                            </button>
                        </div>
                    </form>

                    <!-- Security Tips -->
                    <div class="mt-8 p-6 bg-amber-50 rounded-2xl">
                        <h3 class="font-bold text-gray-800 mb-4">Password Security Tips</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-shield-alt text-amber-600 mt-1"></i>
                                <span>Use a unique password for each account</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-key text-amber-600 mt-1"></i>
                                <span>Use a combination of letters, numbers, and symbols</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-sync-alt text-amber-600 mt-1"></i>
                                <span>Change your password regularly (recommended every 90 days)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-lock text-amber-600 mt-1"></i>
                                <span>Never share your password with anyone</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-user-secret text-amber-600 mt-1"></i>
                                <span>Avoid using personal information in your password</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        button.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        button.className = 'fas fa-eye';
    }
}

// Password validation
const newPasswordInput = document.getElementById('newPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');
const passwordMatchMessage = document.getElementById('passwordMatchMessage');

function validatePassword(password) {
    const checks = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };

    // Update check icons
    document.getElementById('lengthCheck').querySelector('i').className =
        checks.length ? 'fas fa-check-circle text-green-500 text-xs mr-2' : 'fas fa-circle text-gray-300 text-xs mr-2';

    document.getElementById('uppercaseCheck').querySelector('i').className =
        checks.uppercase ? 'fas fa-check-circle text-green-500 text-xs mr-2' : 'fas fa-circle text-gray-300 text-xs mr-2';

    document.getElementById('lowercaseCheck').querySelector('i').className =
        checks.lowercase ? 'fas fa-check-circle text-green-500 text-xs mr-2' : 'fas fa-circle text-gray-300 text-xs mr-2';

    document.getElementById('numberCheck').querySelector('i').className =
        checks.number ? 'fas fa-check-circle text-green-500 text-xs mr-2' : 'fas fa-circle text-gray-300 text-xs mr-2';

    document.getElementById('specialCheck').querySelector('i').className =
        checks.special ? 'fas fa-check-circle text-green-500 text-xs mr-2' : 'fas fa-circle text-gray-300 text-xs mr-2';

    return Object.values(checks).every(check => check);
}

function checkPasswordMatch() {
    const newPassword = newPasswordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    if (confirmPassword === '') {
        passwordMatchMessage.classList.add('hidden');
        return;
    }

    if (newPassword === confirmPassword) {
        passwordMatchMessage.textContent = '✓ Passwords match';
        passwordMatchMessage.className = 'text-sm mt-2 text-green-600';
        passwordMatchMessage.classList.remove('hidden');
        return true;
    } else {
        passwordMatchMessage.textContent = '✗ Passwords do not match';
        passwordMatchMessage.className = 'text-sm mt-2 text-red-600';
        passwordMatchMessage.classList.remove('hidden');
        return false;
    }
}

// Event listeners
newPasswordInput.addEventListener('input', function() {
    validatePassword(this.value);
    checkPasswordMatch();
});

confirmPasswordInput.addEventListener('input', checkPasswordMatch);
</script>
@endsection
