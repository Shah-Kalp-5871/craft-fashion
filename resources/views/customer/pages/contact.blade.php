@extends('customer.layouts.master')

@section('title', 'Contact Us | ' . config('constants.SITE_NAME'))
@section('description', "Get in touch with Craft Fashion customer support. Find answers to FAQs about orders, products, payments, and collaborations.")

@section('content')
<section class="py-8 md:py-12 lg:py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12 lg:mb-16 max-w-3xl mx-auto" data-aos="fade-up">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold font-playfair text-dark mb-4">
                Contact Us – {{ config('constants.SITE_NAME') }}
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-secondary leading-relaxed">
                We're here to help! Whether you have a query about your order, products or payments, 
                feel free to reach out to us through the details below.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Contact Card 1: Phone -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="100">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-phone-alt text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-2">Phone / WhatsApp</h3>
                    <p class="text-secondary mb-4">Speak directly with our customer support team</p>
                    <div class="space-y-3 w-full">
                        <a href="tel:{{ \App\Helpers\SettingsHelper::get('store_phone', config('constants.SITE_PHONE')) }}" class="block py-3 px-4 bg-primary/5 text-primary rounded-lg hover:bg-primary/10 transition-colors font-medium">
                            <i class="fas fa-phone-alt mr-2"></i> {{ \App\Helpers\SettingsHelper::get('store_phone', config('constants.SITE_PHONE')) }}
                        </a>
                        <a href="{{ config('constants.WHATSAPP_LINK') }}" class="block py-3 px-4 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors font-medium">
                            <i class="fab fa-whatsapp mr-2"></i> Chat on WhatsApp
                        </a>
                    </div>
                    <p class="text-sm text-secondary mt-4">
                        <i class="fas fa-clock mr-1"></i>
                        Mon-Sat: 10 AM - 8 PM
                    </p>
                </div>
            </div>

            <!-- Contact Card 2: Email -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-envelope text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-2">Email Support</h3>
                    <p class="text-secondary mb-4">Send us your queries or proposals</p>
                    <a href="mailto:{{ \App\Helpers\SettingsHelper::get('store_email', config('constants.SITE_EMAIL')) }}" class="w-full py-3 px-4 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium truncate">
                        <i class="fas fa-envelope mr-2"></i> {{ \App\Helpers\SettingsHelper::get('store_email', config('constants.SITE_EMAIL')) }}
                    </a>
                    <p class="text-sm text-secondary mt-4">
                        <i class="fas fa-clock mr-1"></i>
                        Response within 24 hours
                    </p>
                </div>
            </div>

            <!-- Contact Card 3: Address -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="300">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-2">Our Store</h3>
                    <p class="text-secondary mb-4">Visit our boutique in Yamuna Nagar</p>
                    <div class="text-left text-dark bg-yellow-50/50 rounded-lg p-4 w-full text-sm">
                        <p class="font-medium whitespace-pre-wrap">{{ \App\Helpers\SettingsHelper::get('store_address', config('constants.SITE_ADDRESS')) }}
{{ \App\Helpers\SettingsHelper::get('store_pincode', config('constants.SITE_PINCODE')) }}</p>
                    </div>
                    <p class="text-sm text-secondary mt-4">
                        <i class="fas fa-building mr-1"></i>
                        Visit us for a trial
                    </p>
                </div>
            </div>
        </div>

        <!-- Support Information Section -->
        <div class="mt-16 bg-white rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100" data-aos="fade-up">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold font-playfair text-dark mb-3">Customer Support Information</h2>
                <p class="text-secondary max-w-3xl mx-auto">Everything you need to know about getting help with your {{ config('constants.SITE_NAME') }} experience</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Support Hours -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Support Hours</h3>
                            <div class="space-y-2 text-secondary">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-blue-500 w-4"></i>
                                    <span>Monday to Saturday</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-blue-500 w-4"></i>
                                    <span>10:00 AM – 8:00 PM IST</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-times text-blue-500 w-4"></i>
                                    <span>Sunday: 11:00 AM – 6:00 PM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Support -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box text-xl text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Order Support</h3>
                            <ul class="space-y-2 text-secondary">
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-green-500 mt-1 w-4"></i>
                                    <span>Order confirmation via Email & SMS</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-green-500 mt-1 w-4"></i>
                                    <span>Tracking updates available in Account</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-green-500 mt-1 w-4"></i>
                                    <span>Fast shipping across India</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Payment & Policies -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-credit-card text-xl text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Payment Methods</h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">Credit/Debit Cards</span>
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">UPI</span>
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">Net Banking</span>
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">Cash on Delivery</span>
                            </div>
                        </div>
                    </div>

                    <!-- Policies -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-alt text-xl text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Important Policies</h3>
                            <div class="space-y-2">
                                <a href="{{ route('customer.page.shipping-policy') }}" class="block text-primary hover:text-primary/80 hover:underline">
                                    • Shipping & Delivery Policy
                                </a>
                                <a href="{{ route('customer.page.privacy') }}" class="block text-primary hover:text-primary/80 hover:underline">
                                    • Privacy Policy
                                </a>
                                <a href="{{ route('customer.page.terms') }}" class="block text-primary hover:text-primary/80 hover:underline">
                                    • Terms of Service
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Questions -->
        <div class="mt-16" data-aos="fade-up">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold font-playfair text-dark mb-3">Frequently Asked Questions</h2>
                <p class="text-secondary max-w-3xl mx-auto">Quick answers to common questions about orders, products, and services</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Column 1 -->
                <div class="space-y-6">
                    <!-- Question 1 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600 font-bold">Q1</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-2">How do I track my order?</h3>
                                <p class="text-secondary">You can track your order status in the "My Orders" section of your account or use the tracking link sent to your email.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 2 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                <span class="text-green-600 font-bold">Q2</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-2">What is your delivery time?</h3>
                                <p class="text-secondary">We aim to dispatch orders within 24-48 hours. Delivery typically takes 3-7 business days depending on your location.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 3 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                <span class="text-purple-600 font-bold">Q3</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-2">Do you offer customization?</h3>
                                <p class="text-secondary">Yes! We offer custom tailoring services. You can visit our store or contact us via WhatsApp for custom requirements.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="space-y-6">
                    <!-- Question 4 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                                <span class="text-yellow-600 font-bold">Q4</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-2">What is your return policy?</h3>
                                <p class="text-secondary">We accept returns for defective items within 7 days. Please check our return policy for full details.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 5 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                                <span class="text-red-600 font-bold">Q5</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-2">Can I modify my order?</h3>
                                <p class="text-secondary">If your order hasn't been shipped, please contact us immediately via phone/WhatsApp to request changes.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 6 -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                <span class="text-indigo-600 font-bold">Q6</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-2">Do you have a physical store?</h3>
                                <p class="text-secondary">Yes, we are located in Yamuna Nagar. Check the "Our Store" section above for the exact address and map.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Promise -->
        <div class="mt-16 bg-gradient-to-r from-primary/5 to-primary/10 rounded-2xl p-8 md:p-12 text-center" data-aos="fade-up">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold font-playfair text-dark mb-4">Our Commitment to You</h2>
                <p class="text-lg text-secondary mb-6">
                    At {{ config('constants.SITE_NAME') }}, we're committed to providing exceptional customer service. 
                    Our team is dedicated to ensuring your shopping experience is seamless and delightful.
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-primary/10">
                        <i class="fas fa-shield-alt text-2xl text-primary mx-auto mb-2"></i>
                        <p class="text-sm font-medium text-dark">Secure Shopping</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-primary/10">
                        <i class="fas fa-truck text-2xl text-primary mx-auto mb-2"></i>
                        <p class="text-sm font-medium text-dark">Fast Delivery</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-primary/10">
                        <i class="fas fa-headset text-2xl text-primary mx-auto mb-2"></i>
                        <p class="text-sm font-medium text-dark">Support 6 Days/Week</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-primary/10">
                        <i class="fas fa-heart text-2xl text-primary mx-auto mb-2"></i>
                        <p class="text-sm font-medium text-dark">Satisfaction Guaranteed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Contact -->
        <div class="mt-16 text-center" data-aos="fade-up">
            <h2 class="text-2xl md:text-3xl font-bold font-playfair text-dark mb-6">Still Have Questions?</h2>
            <p class="text-lg text-secondary mb-8 max-w-2xl mx-auto">
                Don't hesitate to reach out. We're here to help with any questions about our products or your order.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:{{ config('constants.SITE_PHONE') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-colors shadow-lg shadow-primary/20">
                    <i class="fas fa-phone-alt w-5 h-5"></i>
                    Call Us Now
                </a>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=aarasabina@gmail.com" target="_blank" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-dark font-semibold rounded-xl border-2 border-primary hover:bg-primary/5 transition-colors">
                    <i class="fas fa-envelope w-5 h-5"></i>
                    Send Email (Gmail)
                </a>
            </div>
        </div>
    </div>
</section>
@endsection