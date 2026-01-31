@extends('customer.layouts.master')

@section('title', 'About Us | Craft Fashion')
@section('description', "Learn about Craft Fashion - your premier destination for women's, girls' and kids' traditional wear in Yamuna Nagar.")

@push('styles')
<style>
    /* Add to Cart Button animation - Consistent with Index Page */
    .add-to-cart-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .add-to-cart-btn.added {
        background-color: #10B981 !important;
        cursor: not-allowed;
        opacity: 0.9;
    }
    
    .add-to-cart-btn:disabled {
        cursor: not-allowed;
        opacity: 0.7;
    }

    .add-to-cart-btn .original-text {
        display: block;
    }
    
    .add-to-cart-btn .added-text {
        display: none;
    }
    
    .add-to-cart-btn.added .original-text {
        display: none;
    }
    
    .add-to-cart-btn.added .added-text {
        display: block;
    }

    /* Toast Notification Styles */
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

    .toast-notification.success {
        border-left-color: #10B981;
    }

    .toast-notification.info {
        border-left-color: #3B82F6;
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

    .toast-notification.success .toast-icon {
        background: #10B981;
        color: white;
    }

    .toast-notification.info .toast-icon {
        background: #3B82F6;
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

    .toast-close:hover {
        color: #6B7280;
        background: #F3F4F6;
    }

    /* For mobile responsiveness */
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

@section('content')
<!-- Modern Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-primary/10 via-white to-primary/5 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-6">
                <i class="fas fa-heart mr-2"></i>
                Our Story Since 2015
            </div>

            <h1 class="text-5xl md:text-6xl font-bold font-playfair text-dark mb-6 leading-tight">
                Crafting Fashion <span class="text-primary">With Love</span>
            </h1>

            <p class="text-xl text-secondary max-w-2xl mx-auto leading-relaxed">
                Where tradition meets contemporary elegance. Discover the journey behind Yamuna Nagar's most beloved boutique.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                <a href="#our-story"
                    class="bg-primary text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 inline-flex items-center justify-center">
                    <span>Our Journey</span>
                    <i class="fas fa-arrow-down ml-2"></i>
                </a>

                <a href="https://wa.me/919876543210"
                    class="bg-white text-dark px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl border border-primary/20 inline-flex items-center justify-center">
                    <i class="fab fa-whatsapp mr-2 text-primary"></i>
                    <span>Chat With Us</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section id="our-story" class="py-20 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Image Gallery -->
            <div class="relative" data-aos="fade-right">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="rounded-2xl overflow-hidden shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Craft Fashion Store Interior"
                                class="w-full h-80 object-cover hover:scale-110 transition duration-700">
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-2xl transform -rotate-2 hover:rotate-0 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Fabric Selection"
                                class="w-full h-60 object-cover hover:scale-110 transition duration-700">
                        </div>
                    </div>
                    <div class="space-y-6 mt-12">
                        <div class="rounded-2xl overflow-hidden shadow-2xl transform -rotate-3 hover:rotate-0 transition duration-500">
                            <img src="https://img.freepik.com/free-photo/shop-clothing-clothes-shop-hanger-modern-shop-boutique_1150-8886.jpg?semt=ais_hybrid&w=740&q=80"
                                alt="Customer Experience"
                                class="w-full h-60 object-cover hover:scale-110 transition duration-700">
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Y2xvdGhlc3xlbnwwfHwwfHx8MA%3D%3D"
                                alt="Design Collection"
                                class="w-full h-80 object-cover hover:scale-110 transition duration-700">
                        </div>
                    </div>
                </div>

                <!-- Floating Stats -->
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-2xl p-6 border border-primary/10">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary mb-1">8+</div>
                        <div class="text-sm font-medium text-dark">Years of Excellence</div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div data-aos="fade-left">
                <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-star mr-2"></i>
                    Our Legacy
                </div>

                <h2 class="text-4xl md:text-5xl font-bold font-playfair text-dark mb-6 leading-tight">
                    Weaving Stories Through <span class="text-primary">Fashion</span>
                </h2>

                <div class="space-y-6 text-secondary text-lg leading-relaxed">
                    <p>Founded with passion by <strong>Priya Sharma</strong>, Craft Fashion has been the heart of Yamuna Nagar's fashion scene since 2015. What started as a small boutique has blossomed into a cherished destination for those who appreciate the artistry of traditional wear.</p>

                    <p>Our journey began at <strong>123 Fashion Street, Yamuna Nagar</strong> with a simple vision: to create clothing that honors Indian heritage while embracing contemporary elegance. Today, we're proud to serve generations of families who trust us for their most special occasions.</p>

                    <div class="bg-gradient-to-r from-primary/5 to-primary/10 p-6 rounded-2xl border-l-4 border-primary">
                        <p class="italic text-dark font-medium">"Every stitch tells a story, every design celebrates tradition, and every customer becomes family."</p>
                    </div>
                </div>

                <!-- Values Grid -->
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="text-center p-4 rounded-2xl bg-white shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-gem text-primary text-xl"></i>
                        </div>
                        <h4 class="font-bold text-dark mb-1">Premium Quality</h4>
                        <p class="text-secondary text-sm">Finest fabrics & craftsmanship</p>
                    </div>

                    <div class="text-center p-4 rounded-2xl bg-white shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-palette text-primary text-xl"></i>
                        </div>
                        <h4 class="font-bold text-dark mb-1">Unique Designs</h4>
                        <p class="text-secondary text-sm">Exclusive patterns & styles</p>
                    </div>

                    <div class="text-center p-4 rounded-2xl bg-white shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-heart text-primary text-xl"></i>
                        </div>
                        <h4 class="font-bold text-dark mb-1">Personal Touch</h4>
                        <p class="text-secondary text-sm">Tailored to your preferences</p>
                    </div>

                    <div class="text-center p-4 rounded-2xl bg-white shadow-lg border border-primary/10 hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-award text-primary text-xl"></i>
                        </div>
                        <h4 class="font-bold text-dark mb-1">Trusted Legacy</h4>
                        <p class="text-secondary text-sm">8+ years of excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Founder Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-primary/5 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-4">
                    <i class="fas fa-crown mr-2"></i>
                    Meet Our Founder
                </div>
                <h2 class="text-4xl md:text-5xl font-bold font-playfair text-dark mb-4">
                    The Heart Behind <span class="text-primary">Craft Fashion</span>
                </h2>
                <p class="text-xl text-secondary max-w-2xl mx-auto">
                    Passion, vision, and dedication personified
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-primary/10" data-aos="fade-up">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Founder Image -->
                    <div class="lg:col-span-1 relative">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Priya Sharma" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/40 to-transparent"></div>

                        <!-- Floating Badge -->
                        <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur-sm rounded-2xl p-4 shadow-lg">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">15+</div>
                                <div class="text-sm font-medium text-dark">Years Experience</div>
                            </div>
                        </div>
                    </div>

                    <!-- Founder Content -->
                    <div class="lg:col-span-2 p-12">
                        <div class="flex flex-col h-full justify-center">
                            <h3 class="text-3xl font-bold font-playfair text-dark mb-2">Priya Sharma</h3>
                            <p class="text-primary text-xl font-medium mb-6">Founder & Creative Director</p>

                            <div class="space-y-4 text-secondary text-lg leading-relaxed">
                                <p>With over 15 years of expertise in the fashion industry, Priya Sharma brings an unparalleled eye for detail and a deep understanding of traditional craftsmanship to every collection.</p>

                                <p>Her journey began with a simple belief: that fashion should celebrate individuality while honoring heritage. This philosophy is woven into every piece at Craft Fashion, creating garments that are both timeless and contemporary.</p>

                                <p>"I wanted to create a space where women and families could find clothing that makes them feel beautiful, confident, and connected to their roots. Craft Fashion is that dream realized."</p>
                            </div>

                            <div class="flex items-center space-x-6 mt-8">
                                <a href="https://wa.me/919876543210"
                                    class="bg-primary text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary/90 inline-flex items-center">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    <span>Connect With Priya</span>
                                </a>

                                <div class="flex space-x-4">
                                    <a href="#"
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-dark hover:bg-primary hover:text-white transition duration-300">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-dark hover:bg-primary hover:text-white transition duration-300">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products on About Page -->
<section class="py-20 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-sparkles mr-2"></i>
                Customer Favorites
            </div>
            <h2 class="text-4xl md:text-5xl font-bold font-playfair text-dark mb-4">
                Best Selling <span class="text-primary">Collections</span>
            </h2>
            <p class="text-xl text-secondary max-w-2xl mx-auto">
                Discover why our customers keep coming back for these beloved pieces
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Product 1 -->
            <div data-aos="fade-up"
                class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Floral Kurti"
                        class="w-full h-64 object-cover transition duration-500 group-hover:scale-110">

                    <div class="absolute top-4 right-4">
                        <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">Bestseller</span>
                    </div>

                    <div class="absolute top-4 left-4">
                        <button
                            class="wishlist-btn bg-white/90 backdrop-blur-sm w-10 h-10 rounded-full flex items-center justify-center transition-all hover:bg-white hover:scale-110"
                            data-product="about-1">
                            <i class="far fa-heart text-dark"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-dark">Floral Kurti</h3>
                        <div class="text-primary font-bold">₹1,299</div>
                    </div>
                    <p class="text-secondary text-sm mb-3">Cotton | All Sizes Available</p>
                    <button
                        class="add-to-cart-btn bg-primary text-white w-full py-3 rounded-full font-semibold transition hover:bg-primary/90"
                        data-product="about-1">
                        <span class="original-text">Add to Cart</span>
                        <span class="added-text">✓ Added</span>
                    </button>
                </div>
            </div>

            <!-- Product 2 -->
            <div data-aos="fade-up" data-aos-delay="100"
                class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1763719161819-85b100b63f42?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8UHJpbnRlZCUyMFRvcHxlbnwwfHwwfHx8MA%3D%3D"
                        alt="Printed Top"
                        class="w-full h-64 object-cover transition duration-500 group-hover:scale-110">

                    <div class="absolute top-4 left-4">
                        <button
                            class="wishlist-btn bg-white/90 backdrop-blur-sm w-10 h-10 rounded-full flex items-center justify-center transition-all hover:bg-white hover:scale-110"
                            data-product="about-2">
                            <i class="far fa-heart text-dark"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-dark">Printed Top</h3>
                        <div class="text-primary font-bold">₹899</div>
                    </div>
                    <p class="text-secondary text-sm mb-3">Georgette | Trendy Prints</p>
                    <button
                        class="add-to-cart-btn bg-primary text-white w-full py-3 rounded-full font-semibold transition hover:bg-primary/90"
                        data-product="about-2">
                        <span class="original-text">Add to Cart</span>
                        <span class="added-text">✓ Added</span>
                    </button>
                </div>
            </div>

            <!-- Product 3 -->
            <div data-aos="fade-up" data-aos-delay="200"
                class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100">
                <div class="relative overflow-hidden">
                    <img src="https://media.istockphoto.com/id/1340689674/photo/beautiful-attractive-woman-sitting-in-traditional-indian-outfit-posing-for-the-camera-in.webp?a=1&b=1&s=612x612&w=0&k=20&c=k5P-2eZho8P7Ux0oB6BdlJpXAUebD_Q0v5oMyKdvDWU="
                        alt="Kurti Palazzo Set"
                        class="w-full h-64 object-cover transition duration-500 group-hover:scale-110">

                    <div class="absolute top-4 right-4">
                        <span class="bg-dark text-white px-3 py-1 rounded-full text-sm font-medium">New</span>
                    </div>

                    <div class="absolute top-4 left-4">
                        <button
                            class="wishlist-btn bg-white/90 backdrop-blur-sm w-10 h-10 rounded-full flex items-center justify-center transition-all hover:bg-white hover:scale-110"
                            data-product="about-3">
                            <i class="far fa-heart text-dark"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-dark">Kurti Palazzo Set</h3>
                        <div class="text-primary font-bold">₹1,899</div>
                    </div>
                    <p class="text-secondary text-sm mb-3">Complete Outfit | All Sizes</p>
                    <button
                        class="add-to-cart-btn bg-primary text-white w-full py-3 rounded-full font-semibold transition hover:bg-primary/90"
                        data-product="about-3">
                        <span class="original-text">Add to Cart</span>
                        <span class="added-text">✓ Added</span>
                    </button>
                </div>
            </div>

            <!-- Product 4 -->
            <div data-aos="fade-up" data-aos-delay="300"
                class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100">
                <div class="relative overflow-hidden">
                    <img src="https://media.istockphoto.com/id/1473423448/photo/hand-smocked-dress-for-little-girls-isolated-on-grey-background.webp?a=1&b=1&s=612x612&w=0&k=20&c=sXNwsLUdrDwXbYRGznfGagd6LiRcNNL7hYWXBXiji2E="
                        alt="Girls' Frock"
                        class="w-full h-64 object-cover transition duration-500 group-hover:scale-110">

                    <div class="absolute top-4 left-4">
                        <button
                            class="wishlist-btn bg-white/90 backdrop-blur-sm w-10 h-10 rounded-full flex items-center justify-center transition-all hover:bg-white hover:scale-110"
                            data-product="about-4">
                            <i class="far fa-heart text-dark"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-dark">Girls' Frock</h3>
                        <div class="text-primary font-bold">₹799</div>
                    </div>
                    <p class="text-secondary text-sm mb-3">Age 2-10 Years | Colorful</p>
                    <button
                        class="add-to-cart-btn bg-primary text-white w-full py-3 rounded-full font-semibold transition hover:bg-primary/90"
                        data-product="about-4">
                        <span class="original-text">Add to Cart</span>
                        <span class="added-text">✓ Added</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('customer.products.list') }}"
                class="inline-flex items-center bg-primary text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90">
                Explore Full Collection
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Visit Store Section -->
<section class="py-20 bg-gradient-to-br from-primary/5 via-white to-primary/10 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-4">
                    <i class="fas fa-store mr-2"></i>
                    Visit Our Boutique
                </div>
                <h2 class="text-4xl md:text-5xl font-bold font-playfair text-dark mb-4">
                    Experience Craft Fashion <span class="text-primary">In Person</span>
                </h2>
                <p class="text-xl text-secondary max-w-2xl mx-auto">
                    Step into our world of elegance and discover why we're Yamuna Nagar's favorite boutique
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Store Information -->
                <div data-aos="fade-right">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-primary/10 h-full">
                        <h3 class="text-2xl font-bold font-playfair text-dark mb-6">Store Details</h3>

                        <div class="space-y-6">
                            <div class="flex items-start p-4 rounded-2xl bg-primary/5 hover:bg-primary/10 transition duration-300">
                                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-primary text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-dark text-lg mb-1">Our Location</h4>
                                    <p class="text-secondary">123 Fashion Street, Yamuna Nagar, 135001</p>
                                    <p class="text-sm text-primary mt-1">Landmark: Near Huda Market</p>
                                </div>
                            </div>

                            <div class="flex items-start p-4 rounded-2xl bg-primary/5 hover:bg-primary/10 transition duration-300">
                                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-phone-alt text-primary text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-dark text-lg mb-1">Contact Us</h4>
                                    <a href="tel:+919876543210"
                                        class="text-secondary hover:text-primary transition">+91 9876543210</a>
                                    <p class="text-sm text-primary mt-1">Call for appointments or queries</p>
                                </div>
                            </div>

                            <div class="flex items-start p-4 rounded-2xl bg-primary/5 hover:bg-primary/10 transition duration-300">
                                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-clock text-primary text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-dark text-lg mb-1">Opening Hours</h4>
                                    <div class="space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-secondary">Monday - Saturday</span>
                                            <span class="font-medium text-dark">10:00 AM - 8:00 PM</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary">Sunday</span>
                                            <span class="font-medium text-dark">11:00 AM - 6:00 PM</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-primary mt-2">We're here to welcome you!</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-6 bg-gradient-to-r from-primary/5 to-primary/10 rounded-2xl border border-primary/20">
                            <h4 class="font-bold text-dark mb-2">Special Services</h4>
                            <ul class="space-y-2 text-secondary">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Free Home Trial Available
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Custom Tailoring Services
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Personal Styling Consultation
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Map & Store Image -->
                <div data-aos="fade-left">
                    <div class="space-y-6 h-full">
                        <div class="rounded-2xl overflow-hidden shadow-2xl h-64">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3452.769921153434!2d77.2994153151155!3d30.0720729818748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fb5b5dac5e5a5%3A0x4a5a5a5a5a5a5a5a!2sHUDA%20Sector%2017%2C%20Yamuna%20Nagar%2C%20Haryana%20135003!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                class="rounded-2xl"></iframe>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="rounded-2xl overflow-hidden shadow-lg">
                                <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                    alt="Store Interior"
                                    class="w-full h-40 object-cover hover:scale-110 transition duration-700">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg">
                                <img src="https://plus.unsplash.com/premium_photo-1673125287084-e90996bad505?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8Y2xvdGhlc3xlbnwwfHwwfHx8MA%3D%3D"
                                    alt="Customer Service"
                                    class="w-full h-40 object-cover hover:scale-110 transition duration-700">
                            </div>
                        </div>

                        <div class="bg-primary text-white rounded-2xl p-6 text-center">
                            <h4 class="font-bold text-lg mb-2">Ready to Visit?</h4>
                            <p class="mb-4">Book a personal styling session or just drop by!</p>
                            <a href="https://wa.me/919876543210?text=I'd like to visit your store. What's the best time to come?"
                                class="bg-white text-primary px-6 py-3 rounded-full font-semibold transition-all hover:scale-105 inline-flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Confirm Visit Timing
                            </a>
                        </div>
                    </div>
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
        <h2 data-aos="fade-up" class="text-4xl md:text-6xl font-bold font-playfair mb-6">
            Become Part of Our Story
        </h2>
        <p data-aos="fade-up" data-aos-delay="100" class="text-xl md:text-2xl mb-8 text-white/90 max-w-3xl mx-auto">
            Join thousands of satisfied customers who have made Craft Fashion their trusted fashion destination
        </p>

        <div data-aos="fade-up" data-aos-delay="200" class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="{{ route('customer.products.list') }}"
                class="group bg-white text-dark px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl inline-flex items-center justify-center">
                <span>Shop Collection</span>
                <i class="fas fa-shopping-bag ml-3 group-hover:scale-110 transition-transform"></i>
            </a>

            <a href="https://wa.me/919876543210"
                class="group bg-primary text-white px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl inline-flex items-center justify-center border-2 border-primary/30">
                <i class="fab fa-whatsapp mr-3 text-xl"></i>
                <span>Get Personal Advice</span>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Cart and Wishlist functionality with Button State Changes for About Page
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize cart and wishlist from localStorage
        let cart = JSON.parse(localStorage.getItem('craftFashionCart')) || [];
        let wishlist = JSON.parse(localStorage.getItem('craftFashionWishlist')) || [];

        // Update cart and wishlist icons
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = cart.length;
                cartCount.style.display = cart.length > 0 ? 'flex' : 'none';
            }
        }

        function updateWishlistCount() {
            const wishlistCount = document.getElementById('wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = wishlist.length;
                wishlistCount.style.display = wishlist.length > 0 ? 'flex' : 'none';
            }
        }

        // Create a custom toast notification function
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    ${type === 'success' ? '✓' : type === 'info' ? 'ℹ' : '❤'}
                </div>
                <div class="toast-message">${message}</div>
                <div class="toast-close">&times;</div>
            `;

            // Add to body
            document.body.appendChild(toast);

            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);

            // Close on click
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            });
        }

        // Add to Cart functionality for About Page products
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            // Initialize button state based on localStorage
            const productId = button.getAttribute('data-product');
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                button.classList.add('added');
                button.disabled = true;
            }

            button.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent event bubbling
                
                const productId = this.getAttribute('data-product');
                const productCard = this.closest('.group') || this.closest('.bg-white');
                const productName = productCard.querySelector('h3')?.textContent || 'Product';
                const productPriceElement = productCard.querySelector('.text-primary, .font-bold');
                const productPrice = productPriceElement?.textContent || '₹0';
                const productImage = productCard.querySelector('img')?.src || '';

                // Check if product already in cart
                const existingItem = cart.find(item => item.id === productId);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: 1
                    });
                }

                // Save to localStorage and update UI
                localStorage.setItem('craftFashionCart', JSON.stringify(cart));
                updateCartCount();

                // Change button state to "Added" with green color and disable it
                this.classList.add('added');
                this.disabled = true;

                // Show toast notification
                showToast(`${productName} added to cart`, 'success');
            });
        });

        // Wishlist functionality for About Page products
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent event bubbling
                
                const productId = this.getAttribute('data-product');
                const productCard = this.closest('.group') || this.closest('.bg-white');
                const productName = productCard.querySelector('h3')?.textContent || 'Product';
                const productPriceElement = productCard.querySelector('.text-primary, .font-bold');
                const productPrice = productPriceElement?.textContent || '₹0';
                const productImage = productCard.querySelector('img')?.src || '';
                const heartIcon = this.querySelector('i');

                // Check if product already in wishlist
                const existingItemIndex = wishlist.findIndex(item => item.id === productId);

                if (existingItemIndex !== -1) {
                    // Remove from wishlist
                    wishlist.splice(existingItemIndex, 1);
                    heartIcon.classList.remove('fas', 'text-red-500');
                    heartIcon.classList.add('far');
                    showToast(`${productName} removed from wishlist`, 'info');
                } else {
                    // Add to wishlist
                    wishlist.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage
                    });

                    heartIcon.classList.remove('far');
                    heartIcon.classList.add('fas', 'text-red-500');
                    showToast(`${productName} added to wishlist`, 'success');
                }

                // Save to localStorage and update UI
                localStorage.setItem('craftFashionWishlist', JSON.stringify(wishlist));
                updateWishlistCount();
            });
        });

        // Initialize wishlist button states
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            const productId = button.getAttribute('data-product');
            const heartIcon = button.querySelector('i');

            if (wishlist.find(item => item.id === productId)) {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas', 'text-red-500');
            }
        });

        // Initialize cart and wishlist counts
        updateCartCount();
        updateWishlistCount();
    });
</script>
@endpush
@endsection