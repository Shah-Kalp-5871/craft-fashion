@extends('customer.layouts.master')

@section('title', 'Change Password | Craft Fashion')

@section('content')
<!-- Page Header -->
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold font-playfair text-dark mb-2">My Account</h1>
                <nav class="flex text-sm text-secondary">
                    <a href="{{ route('customer.home.index') }}" class="hover:text-primary transition-colors">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('customer.account.profile') }}" class="hover:text-primary transition-colors">Account</a>
                    <span class="mx-2">/</span>
                    <span class="text-dark font-medium">Change Password</span>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="p-6 bg-primary/5 border-b border-gray-100 text-center">
                        <div class="w-20 h-20 bg-white rounded-full mx-auto shadow-sm flex items-center justify-center mb-3">
                            <span class="text-3xl font-bold text-primary">{{ substr($customer->name, 0, 1) }}</span>
                        </div>
                        <h3 class="font-bold text-dark text-lg truncate">{{ $customer->name }}</h3>
                        <p class="text-secondary text-sm truncate">{{ $customer->email }}</p>
                    </div>
                    
                    <nav class="p-2 space-y-1">
                        <a href="{{ route('customer.account.profile') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-user w-6"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('customer.account.orders') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-shopping-bag w-6"></i>
                            <span>My Orders</span>
                        </a>
                        <a href="{{ route('customer.account.addresses') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-map-marker-alt w-6"></i>
                            <span>Addresses</span>
                        </a>
                        <a href="{{ route('customer.wishlist.index') }}" class="flex items-center px-4 py-3 text-secondary hover:bg-gray-50 hover:text-dark font-medium rounded-xl transition-colors">
                            <i class="fas fa-heart w-6"></i>
                            <span>Wishlist</span>
                        </a>
                        <a href="{{ route('customer.account.change-password') }}" class="flex items-center px-4 py-3 bg-primary/5 text-primary font-medium rounded-xl transition-colors">
                            <i class="fas fa-lock w-6"></i>
                            <span>Change Password</span>
                        </a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="border-t border-gray-100 mt-2 pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-red-500 hover:bg-red-50 font-medium rounded-xl transition-colors">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-lg text-dark">Change Password</h3>
                        <p class="text-secondary text-sm">Update your account password to stay secure.</p>
                    </div>
                    
                    <div class="p-8">
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 flex items-center">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('customer.account.update-password') }}" method="POST" class="max-w-md">
                            @csrf
                            <div class="space-y-6">
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password" class="block text-sm font-semibold text-dark mb-2">Current Password</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" id="current_password" 
                                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-200 focus:border-primary' }} focus:outline-none transition-colors"
                                            placeholder="••••••••" required>
                                        <button type="button" onclick="togglePassword('current_password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary hover:text-dark">
                                            <i class="fas fa-eye" id="current_password_icon"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-dark mb-2">New Password</label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password" 
                                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-200 focus:border-primary' }} focus:outline-none transition-colors"
                                            placeholder="••••••••" required>
                                        <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary hover:text-dark">
                                            <i class="fas fa-eye" id="password_icon"></i>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-xs text-secondary">Minimum 8 characters. Must be a strong password.</p>
                                    @error('password')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm New Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-dark mb-2">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:outline-none transition-colors"
                                            placeholder="••••••••" required>
                                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary hover:text-dark">
                                            <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-dark transition-all duration-300 shadow-lg shadow-primary/20">
                                        Update Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
