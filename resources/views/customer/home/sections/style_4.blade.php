@if(isset($section) && count($section['products']) > 0)
<section class="py-16 bg-gray-50" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            @if($section['subtitle'])
            <span class="text-primary font-medium tracking-wider uppercase text-sm mb-2 block">{{ $section['subtitle'] }}</span>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold font-playfair text-dark mb-4">{{ $section['title'] }}</h2>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        <!-- Style 4: Center Featured + Side Items -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            
            @php
                $products = collect($section['products']); // Convert to collection for easier handling
                $mainProduct = $products->first();
                $restProducts = $products->slice(1, 4); 
            @endphp

            @if($mainProduct)
                 @php
                    $mName = is_array($mainProduct) ? $mainProduct['name'] : $mainProduct->name;
                    $mSlug = is_array($mainProduct) ? $mainProduct['slug'] : $mainProduct->slug;
                    $mPrice = is_array($mainProduct) ? $mainProduct['price'] : $mainProduct->price;
                    $mImage = is_array($mainProduct) ? ($mainProduct['main_image'] ?? '') : ($mainProduct->main_image ?? '');
                @endphp
                <!-- Main Featured Item (Mobile: Order 1, Desktop: Order 2 (Middle)) -->
                <div class="lg:col-start-2 lg:row-start-1 order-1 lg:order-2 h-full">
                    <div class="group relative rounded-3xl overflow-hidden shadow-2xl h-full aspect-[3/4]">
                        <img src="{{ Str::startsWith($mImage, 'http') ? $mImage : asset('storage/' . $mImage) }}" 
                             alt="{{ $mName }}" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-90"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-center transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                             <h3 class="text-2xl font-bold font-playfair text-white mb-2">{{ $mName }}</h3>
                             <p class="text-white/90 text-lg mb-4">₹{{ number_format($mPrice) }}</p>
                             <a href="{{ route('customer.products.details', $mSlug) }}" class="inline-block px-8 py-3 bg-white text-dark rounded-full font-bold hover:bg-primary hover:text-white transition-colors">
                                Shop Now
                             </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Left Column Items (Mobile: Order 2, Desktop: Order 1) -->
            <div class="lg:col-start-1 lg:row-start-1 order-2 lg:order-1 flex flex-col gap-6">
                 @foreach($restProducts->slice(0, 2) as $product)
                    @include('customer.home.sections.partials.product-card-style-4-side', ['product' => $product])
                 @endforeach
            </div>

            <!-- Right Column Items (Mobile: Order 3, Desktop: Order 3) -->
            <div class="lg:col-start-3 lg:row-start-1 order-3 lg:order-3 flex flex-col gap-6">
                @foreach($restProducts->slice(2, 2) as $product)
                     @include('customer.home.sections.partials.product-card-style-4-side', ['product' => $product])
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
