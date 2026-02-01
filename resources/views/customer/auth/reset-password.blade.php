@extends('customer.layouts.master')

@section('title', config('app.name') . ' | Reset Password')

@section('content')

<!-- Reset Password Section -->
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
            <p class="text-secondary text-lg">Create a new secure password</p>
        </div>

        <div class="max-w-md mx-auto">
            <!-- Reset Password Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-dark to-primary text-white p-8">
                    <h2 class="text-3xl font-playfair font-bold text-center">Reset Password</h2>
                    <p class="text-white/80 text-center mt-2">Enter your new password below</p>
                </div>

                <!-- Form -->
                <div class="p-8">
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

                    <form method="POST" action="{{ route('customer.reset-password.update') }}" id="resetPasswordForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- Password Input -->
                        <div class="mb-6">
                            <label for="password" class="block text-dark font-medium mb-2">
                                <i class="fas fa-lock mr-2 text-primary"></i>New Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                    placeholder="Create strong password"
                                    required>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-8">
                            <label for="password_confirmation" class="block text-dark font-medium mb-2">
                                <i class="fas fa-lock mr-2 text-primary"></i>Confirm Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200"
                                    placeholder="Confirm new password"
                                    required>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-dark to-primary text-white py-4 rounded-xl font-semibold text-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group mb-6">
                            <span>Update Password</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .font-playfair {
        font-family: 'Playfair Display', serif;
    }
</style>
@endpush
