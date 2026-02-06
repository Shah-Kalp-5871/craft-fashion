@if(isset($section) && count($section['products']) > 0)
<section class="py-16 bg-white overflow-hidden" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-16">
            @if($section['subtitle'])
            <span class="text-primary font-medium tracking-wider uppercase text-sm mb-2 block">{{ $section['subtitle'] }}</span>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold font-playfair text-dark">{{ $section['title'] }}</h2>
        </div>

        <!-- Style 5: Diagonal Staggered Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            @foreach($section['products'] as $index => $product)
                @php
                    $pName = is_array($product) ? $product['name'] : $product->name;
                    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
                    $pPrice = is_array($product) ? $product['price'] : $product->price;
                    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
                    
                    // Stagger effect: Push even items down
                    $marginTop = ($index % 2 != 0) ? 'md:mt-12' : '';
                @endphp
                <div class="{{ $marginTop }} transition-all duration-500 hover:-translate-y-2">
                    <div class="group relative rounded-t-full rounded-b-2xl overflow-hidden aspect-[3/4] shadow-lg">
                        <a href="{{ route('customer.products.details', $pSlug) }}">
                            <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
                                 alt="{{ $pName }}" 
                                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        </a>
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 to-transparent translate-y-full group-hover:translate-y-0 transition duration-300">
                             <h3 class="text-white font-bold text-lg mb-1 truncate">{{ $pName }}</h3>
                             <p class="text-white/90">₹{{ number_format($pPrice) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
