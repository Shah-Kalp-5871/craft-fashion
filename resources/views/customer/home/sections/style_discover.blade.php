@if(isset($section))
<section class="py-20 bg-gradient-to-br from-purple-50 via-white to-pink-50" data-aos="fade-up">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center bg-purple-100 text-purple-900 px-5 py-2 rounded-full text-sm font-semibold mb-4 shadow-sm">
                <i class="fas fa-sparkles mr-2"></i>
                SHOP BY CATEGORY
            </div>
            <h2 class="text-5xl md:text-6xl font-bold font-playfair text-gray-900 mb-4 leading-tight">
                {{ $section['title'] }}
            </h2>
            @if($section['subtitle'])
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ $section['subtitle'] }}
            </p>
            @endif
        </div>

        <!-- Category Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Women's Collection -->
            <a href="{{ route('customer.category.products', ['slug' => 'women']) }}" class="category-card group block">
                <div class="relative overflow-hidden rounded-2xl shadow-xl shine-effect h-[450px]">
                    <div class="image-overlay absolute inset-0 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                    <img src="https://images.unsplash.com/photo-1763771444851-4fa902534f04?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTB8fHdvbWVucyUyMHdlYXIlMjBjbG90aGVzfGVufDB8fDB8fHww"
                        alt="Women's Collection"
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-110">

                    <div class="absolute inset-0 z-20 p-8 flex flex-col justify-end">
                        <div class="mb-4">
                            <span class="badge bg-white/25 px-4 py-1.5 rounded-full text-sm font-semibold text-white">
                                <i class="fas fa-star mr-1"></i>
                                NEW ARRIVALS
                            </span>
                        </div>
                        <h3 class="text-3xl font-bold font-playfair mb-3 text-white">Women's Wear</h3>
                        <p class="text-white/90 mb-6 text-base">Elegant kurtis & boutique sets</p>
                        <div class="cta-button inline-flex items-center bg-white text-gray-900 px-6 py-3 rounded-full font-bold text-sm w-fit shadow-lg">
                            Shop Now
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>

                    <!-- Corner Price Tag -->
                    <div class="absolute top-6 right-6 z-20 bg-purple-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                        <i class="fas fa-tags mr-1"></i> From ₹499
                    </div>
                </div>
            </a>

            <!-- Men's Collection -->
            <a href="{{ route('customer.category.products', ['slug' => 'men']) }}" class="category-card group block">
                <div class="relative overflow-hidden rounded-2xl shadow-xl shine-effect h-[450px]">
                    <div class="image-overlay absolute inset-0 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                    <img src="https://plus.unsplash.com/premium_photo-1706806943425-e5cc742f62e0?q=80&w=715&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="Men's Collection"
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-110">

                    <div class="absolute inset-0 z-20 p-8 flex flex-col justify-end">
                        <div class="mb-4">
                            <span class="badge bg-blue-500/90 px-4 py-1.5 rounded-full text-sm font-semibold text-white">
                                <i class="fas fa-bolt mr-1"></i>
                                PREMIUM
                            </span>
                        </div>
                        <h3 class="text-3xl font-bold font-playfair mb-3 text-white">Men's Wear</h3>
                        <p class="text-white/90 mb-6 text-base">Classic shirts & ethnic wear</p>
                        <div class="cta-button inline-flex items-center bg-white text-gray-900 px-6 py-3 rounded-full font-bold text-sm w-fit shadow-lg">
                            Shop Now
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>

                    <!-- Corner Price Tag -->
                    <div class="absolute top-6 right-6 z-20 bg-blue-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                        <i class="fas fa-tags mr-1"></i> From ₹599
                    </div>
                </div>
            </a>

            <!-- Girls' Collection -->
            <a href="{{ route('customer.category.products', ['slug' => 'girl']) }}" class="category-card group block">
                <div class="relative overflow-hidden rounded-2xl shadow-xl shine-effect h-[450px]">
                    <div class="image-overlay absolute inset-0 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                    <img src="https://plus.unsplash.com/premium_photo-1693221161783-b00475661aa9?w=600&auto=format&fit=crop&q=60"
                        alt="Girls' Collection"
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-110">

                    <div class="absolute inset-0 z-20 p-8 flex flex-col justify-end">
                        <div class="mb-4">
                            <span class="badge bg-pink-500/90 px-4 py-1.5 rounded-full text-sm font-semibold text-white">
                                <i class="fas fa-fire mr-1"></i>
                                TRENDING
                            </span>
                        </div>
                        <h3 class="text-3xl font-bold font-playfair mb-3 text-white">Girls' Wear</h3>
                        <p class="text-white/90 mb-6 text-base">Charming outfits for girls</p>
                        <div class="cta-button inline-flex items-center bg-white text-gray-900 px-6 py-3 rounded-full font-bold text-sm w-fit shadow-lg">
                            Shop Now
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>

                    <!-- Corner Price Tag -->
                    <div class="absolute top-6 right-6 z-20 bg-pink-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                        <i class="fas fa-tags mr-1"></i> From ₹399
                    </div>
                </div>
            </a>

            <!-- Kids' Collection -->
            <a href="{{ route('customer.category.products', ['slug' => 'kids']) }}" class="category-card group block">
                <div class="relative overflow-hidden rounded-2xl shadow-xl shine-effect h-[450px]">
                    <div class="image-overlay absolute inset-0 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                    <img src="https://media.istockphoto.com/id/118198649/photo/kids.webp?a=1&b=1&s=612x612&w=0&k=20&c=MFQgIet-YztpVAjM2RIKF0OS3H6-Yy6lUVgqbdTcTXw="
                        alt="Kids' Collection"
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-110">

                    <div class="absolute inset-0 z-20 p-8 flex flex-col justify-end">
                        <div class="mb-4">
                            <span class="badge bg-green-500/90 px-4 py-1.5 rounded-full text-sm font-semibold text-white">
                                <i class="fas fa-heart mr-1"></i>
                                COMFORT
                            </span>
                        </div>
                        <h3 class="text-3xl font-bold font-playfair mb-3 text-white">Kids' Wear</h3>
                        <p class="text-white/90 mb-6 text-base">Comfortable everyday wear</p>
                        <div class="cta-button inline-flex items-center bg-white text-gray-900 px-6 py-3 rounded-full font-bold text-sm w-fit shadow-lg">
                            Shop Now
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>

                    <!-- Corner Price Tag -->
                    <div class="absolute top-6 right-6 z-20 bg-green-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                        <i class="fas fa-tags mr-1"></i> From ₹349
                    </div>
                </div>
            </a>
        </div>

        <!-- Bottom CTA -->
        <div class="text-center mt-16">
            <p class="text-gray-600 mb-6 text-lg">Can't decide? Browse our entire collection</p>
            <a href="{{ route('customer.products.list') }}" class="inline-flex items-center bg-gray-900 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-800 transition-all hover:scale-105 shadow-xl">
                View All Products
                <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>
    </div>
</section>
@endif
