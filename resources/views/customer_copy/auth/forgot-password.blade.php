@extends('customer.layouts.minimal')

@section('title', 'Forgot Password - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-amber-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('customer.home.index') }}" class="inline-block">
                <h1 class="text-4xl font-bold text-amber-800">{{ config('app.name') }}</h1>
            </a>
            <p class="text-gray-600 mt-2">Reset your password</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <p class="text-gray-600 mb-6 text-center">
                Enter your email address and we'll send you instructions to reset your password.
            </p>
            
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none"
                           placeholder="user@example.com">
                </div>
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-shadow">
                    Send Reset Link
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    <a href="{{ route('customer.login') }}" class="text-amber-600 hover:text-amber-800">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Login
                    </a>
                </p>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        <a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800">
                            <i class="fas fa-home mr-1"></i>
                            Back to Home
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection