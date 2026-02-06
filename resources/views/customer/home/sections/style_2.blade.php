@if(isset($section) && count($section['products']) > 0)
<section class="py-16 bg-white" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            @if($section['subtitle'])
            <span class="text-primary font-medium tracking-wider uppercase text-sm mb-2 block">{{ $section['subtitle'] }}</span>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold font-playfair text-dark mb-4">{{ $section['title'] }}</h2>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        <!-- Style 2: Masonry Grid -->
        <div class="columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
            @foreach($section['products'] as $product)
                @php
                    $pName = is_array($product) ? $product['name'] : $product->name;
                    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
                    $pPrice = is_array($product) ? $product['price'] : $product->price;
                    $pCompare = is_array($product) ? ($product['compare_price'] ?? 0) : ($product->compare_price ?? 0);
                    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
                @endphp
                <div class="break-inside-avoid">
                    <div class="group relative rounded-2xl overflow-hidden shadow-lg bg-white">
                        <a href="{{ route('customer.products.details', $pSlug) }}">
                            <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
                                 alt="{{ $pName }}" 
                                 class="w-full h-auto object-cover transition duration-700 group-hover:scale-110"
                                 onerror="this.src='https://placehold.co/400x500?text={{ urlencode($pName) }}'">
                        </a>
                        
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-dark mb-2 group-hover:text-primary transition-colors">{{ $pName }}</h3>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-dark text-lg">₹{{ number_format($pPrice) }}</span>
                                    @if($pCompare > $pPrice)
                                        <span class="text-sm text-gray-400 line-through">₹{{ number_format($pCompare) }}</span>
                                    @endif
                                </div>
                                <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-dark hover:bg-primary hover:text-white transition-colors">
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
