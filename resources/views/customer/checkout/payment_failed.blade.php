@extends('customer.layouts.master')

@section('title', 'Payment Failed | Craft Fashion')
@section('description', 'Payment failed - Please try again or contact support')

@section('content')
<section class="py-20 bg-gradient-to-br from-red-50 via-white to-red-10 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-72 h-72 bg-red-100 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-red-50 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <div class="inline-flex items-center bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Payment Issue
            </div>

            <div class="relative mb-8">
                <div class="w-32 h-32 mx-auto bg-red-100 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-times-circle text-red-500 text-6xl"></i>
                </div>
                <div class="absolute -top-2 -right-2 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-sad-tear text-red-400 text-xl"></i>
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold font-playfair text-dark mb-6 leading-tight">
                Payment <span class="text-red-500">Failed</span>
            </h1>

            <p class="text-xl text-secondary max-w-xl mx-auto leading-relaxed mb-8">
                We couldn't process your payment. This could be due to various reasons like insufficient funds, incorrect details, or technical issues.
            </p>

            <!-- Error Details Box -->
            <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-red-200">
                <div class="flex items-start mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-info-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-bold text-dark text-lg mb-2">What might have happened?</h3>
                        <ul class="text-secondary space-y-1">
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-400 text-xs mt-1 mr-2"></i>
                                Insufficient funds in your account
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-400 text-xs mt-1 mr-2"></i>
                                Incorrect card details or expiry date
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-400 text-xs mt-1 mr-2"></i>
                                Bank server temporarily unavailable
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-400 text-xs mt-1 mr-2"></i>
                                Daily transaction limit exceeded
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Reference ID (if available) -->
                <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-receipt mr-2"></i>
                        Reference ID: 
                        <span class="font-mono font-bold">CF-<?php echo date('YmdHis'); ?></span>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('customer.checkout.index') }}"
                    class="group bg-primary text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 inline-flex items-center justify-center">
                    <i class="fas fa-redo mr-3 group-hover:rotate-180 transition-transform duration-500"></i>
                    <span>Try Payment Again</span>
                </a>

                <a href="https://wa.me/919876543210?text=Payment%20Failed%20Reference:%20CF-<?php echo date('YmdHis'); ?>"
                    class="group bg-dark text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-dark/90 inline-flex items-center justify-center">
                    <i class="fab fa-whatsapp mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                    <span>Contact Support</span>
                </a>
            </div>

            <!-- Alternative Payment Options -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-dark mb-4">Try Alternative Payment Methods</h3>
                <div class="flex justify-center space-x-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                            <i class="fab fa-google-pay text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-secondary">Google Pay</span>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                            <i class="fab fa-phonepe text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-secondary">PhonePe</span>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-university text-green-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-secondary">Net Banking</span>
                    </div>
                </div>
            </div>

            <!-- Safety Assurance -->
            <div class="mt-8 p-6 bg-white rounded-2xl shadow-sm border border-primary/20">
                <div class="flex items-center justify-center space-x-6">
                    <div class="flex items-center">
                        <i class="fas fa-lock text-green-500 text-xl mr-2"></i>
                        <span class="text-sm text-secondary">100% Secure</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-500 text-xl mr-2"></i>
                        <span class="text-sm text-secondary">SSL Encrypted</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-history text-green-500 text-xl mr-2"></i>
                        <span class="text-sm text-secondary">No Money Deducted</span>
                    </div>
                </div>
                <p class="text-xs text-center text-secondary mt-4">
                    Your money is safe. No amount was deducted from your account.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Check Order Status -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-gray-50 rounded-3xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-question-circle text-primary text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold font-playfair text-dark">Need Help?</h2>
                        <p class="text-secondary">Check your order status or get in touch with our support team</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-dark mb-3 flex items-center">
                            <i class="fas fa-search mr-2 text-primary"></i>
                            Check Order Status
                        </h3>
                        <p class="text-sm text-secondary mb-4">Enter your order ID to check the current status</p>
                        <div class="flex gap-2">
                            <input type="text" placeholder="Order ID" 
                                   class="flex-1 px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button class="bg-primary text-white px-4 py-2 rounded-xl hover:bg-primary/90 transition">
                                Check
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-dark mb-3 flex items-center">
                            <i class="fas fa-headset mr-2 text-primary"></i>
                            Customer Support
                        </h3>
                        <p class="text-sm text-secondary mb-4">Our team is available 9AM-9PM</p>
                        <div class="space-y-3">
                            <a href="tel:+919876543210" 
                               class="flex items-center text-secondary hover:text-primary transition">
                                <i class="fas fa-phone-alt mr-2"></i>
                                +91 98765 43210
                            </a>
                            <a href="mailto:support@craftfashion.com" 
                               class="flex items-center text-secondary hover:text-primary transition">
                                <i class="fas fa-envelope mr-2"></i>
                                support@craftfashion.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Common Payment Issues -->
<section class="py-16 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold font-playfair text-dark mb-4">
                    Common Payment <span class="text-primary">Solutions</span>
                </h2>
                <p class="text-xl text-secondary max-w-2xl mx-auto">
                    Quick fixes for most payment issues
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Solution 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                    <div class="flex items-start mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-dark text-lg mb-2">Check Card Details</h3>
                            <p class="text-secondary text-sm">Ensure your card number, expiry date, and CVV are correct.</p>
                        </div>
                    </div>
                    <button class="text-primary text-sm font-medium hover:text-primary/80 transition flex items-center">
                        <span>How to verify →</span>
                    </button>
                </div>

                <!-- Solution 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                    <div class="flex items-start mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-mobile-alt text-blue-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-dark text-lg mb-2">Verify with Bank</h3>
                            <p class="text-secondary text-sm">Check with your bank if international transactions are enabled.</p>
                        </div>
                    </div>
                    <button class="text-primary text-sm font-medium hover:text-primary/80 transition flex items-center">
                        <span>Bank guidelines →</span>
                    </button>
                </div>

                <!-- Solution 3 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                    <div class="flex items-start mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-wifi text-purple-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-dark text-lg mb-2">Network Issues</h3>
                            <p class="text-secondary text-sm">Switch to a stronger internet connection and try again.</p>
                        </div>
                    </div>
                    <button class="text-primary text-sm font-medium hover:text-primary/80 transition flex items-center">
                        <span>Troubleshoot →</span>
                    </button>
                </div>

                <!-- Solution 4 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                    <div class="flex items-start mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-sync-alt text-orange-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-dark text-lg mb-2">Clear Cache</h3>
                            <p class="text-secondary text-sm">Clear browser cache and cookies, then reload the page.</p>
                        </div>
                    </div>
                    <button class="text-primary text-sm font-medium hover:text-primary/80 transition flex items-center">
                        <span>Clear instructions →</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-20 bg-gradient-to-r from-dark to-primary text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/2 translate-y-1/2"></div>

    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-4xl md:text-6xl font-bold font-playfair mb-6">
            Don't Worry, We're Here to Help!
        </h2>
        <p class="text-xl md:text-2xl mb-8 text-white/90 max-w-3xl mx-auto">
            Your satisfaction is our priority. Contact us anytime for assistance with payments or orders.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="{{ route('customer.home.index') }}"
                class="group bg-white text-dark px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl inline-flex items-center justify-center">
                <i class="fas fa-home mr-3 group-hover:scale-110 transition-transform"></i>
                <span>Back to Home</span>
            </a>

            <a href="https://wa.me/919876543210"
                class="group bg-primary text-white px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl inline-flex items-center justify-center border-2 border-primary/30">
                <i class="fab fa-whatsapp mr-3 text-xl"></i>
                <span>Instant Support</span>
            </a>
        </div>

        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-bold text-white mb-2">24/7</div>
                <div class="text-white/80">Support Available</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-white mb-2">100%</div>
                <div class="text-white/80">Secure Transactions</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-white mb-2">5 Min</div>
                <div class="text-white/80">Average Response Time</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-white mb-2">5000+</div>
                <div class="text-white/80">Happy Customers</div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to the error icon
        const errorIcon = document.querySelector('.fa-times-circle');
        errorIcon.style.animation = 'pulse 2s infinite';

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            .solution-card {
                transition: all 0.3s ease;
            }
            
            .solution-card:hover {
                transform: translateY(-5px);
            }
        `;
        document.head.appendChild(style);

        // Add hover effects to solution cards
        document.querySelectorAll('.bg-white.rounded-2xl').forEach(card => {
            card.classList.add('solution-card');
        });

        // Order status check button
        const checkOrderBtn = document.querySelector('button.bg-primary');
        const orderIdInput = document.querySelector('input[placeholder="Order ID"]');
        
        checkOrderBtn?.addEventListener('click', function() {
            if (!orderIdInput.value.trim()) {
                orderIdInput.focus();
                orderIdInput.style.borderColor = '#ef4444';
                setTimeout(() => {
                    orderIdInput.style.borderColor = '#e5e7eb';
                }, 2000);
            } else {
                // Simulate checking order status
                this.innerHTML = '<div class="spinner border-2 border-white border-t-transparent rounded-full w-5 h-5 animate-spin mx-auto"></div>';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = 'Check';
                    this.disabled = false;
                    alert('Order status: Payment Pending\nPlease complete the payment to confirm your order.');
                }, 1500);
            }
        });
    });
</script>
@endpush
@endsection