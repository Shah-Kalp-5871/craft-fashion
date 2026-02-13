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

        <!-- 3 Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($section['products'] as $product)
                @php
                    // Ensure we have an array, or object property access
                    $pName = is_array($product) ? $product['name'] : $product->name;
                    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
                    $pPrice = is_array($product) ? $product['price'] : $product->price;
                    $pCompare = is_array($product) ? ($product['compare_price'] ?? 0) : ($product->compare_price ?? 0);
                    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
                    $pId = is_array($product) ? $product['id'] : $product->id;
                    $pVariantId = is_array($product) ? ($product['default_variant_id'] ?? null) : ($product->default_variant_id ?? null);
                @endphp
                <div class="group">
                    <div class="relative overflow-hidden rounded-2xl mb-4 aspect-[4/5]">
                        <a href="{{ route('customer.products.details', $pSlug) }}">
                            <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
                                 alt="{{ $pName }}" 
                                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
                                 onerror="this.onerror=null;this.src='/storage/images/placeholder-product.jpg';">
                        </a>
                        
                        <!-- Actions -->
                        @if($pVariantId)
                        <div class="absolute bottom-4 left-0 right-0 px-4 translate-y-full group-hover:translate-y-0 transition duration-300">
                            <button class="add-to-cart-btn w-full bg-white text-dark py-3 rounded-xl font-bold shadow-lg hover:bg-dark hover:text-white transition-colors flex items-center justify-center gap-2"
                                    data-variant-id="{{ $pVariantId }}">
                                <i class="fas fa-shopping-bag"></i> Add to Cart
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <a href="{{ route('customer.products.details', $pSlug) }}">
                            <h3 class="font-bold text-lg text-dark mb-1 group-hover:text-primary transition-colors line-clamp-1">{{ $pName }}</h3>
                        </a>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-dark">₹{{ number_format($pPrice) }}</span>
                            @if($pCompare > $pPrice)
                                <span class="text-sm text-gray-400 line-through">₹{{ number_format($pCompare) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
