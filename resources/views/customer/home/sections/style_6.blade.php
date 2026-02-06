@if(isset($section) && count($section['products']) > 0)
<section class="py-16 bg-gradient-to-r from-gray-50 to-white" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between mb-12">
            <div>
                 @if($section['subtitle'])
                <span class="text-primary font-medium tracking-wider uppercase text-sm mb-2 block">{{ $section['subtitle'] }}</span>
                @endif
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-dark">{{ $section['title'] }}</h2>
            </div>
            <a href="#" class="hidden md:inline-flex items-center text-dark font-bold hover:text-primary transition-colors">
                View Collection <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Style 6: Circular Layout -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 text-center">
            @foreach($section['products'] as $product)
                @php
                    $pName = is_array($product) ? $product['name'] : $product->name;
                    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
                    $pPrice = is_array($product) ? $product['price'] : $product->price;
                    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
                @endphp
                <div class="group">
                    <div class="relative w-40 h-40 md:w-48 md:h-48 mx-auto mb-6 rounded-full overflow-hidden shadow-xl border-4 border-white ring-1 ring-gray-100">
                        <a href="{{ route('customer.products.details', $pSlug) }}">
                            <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
                                 alt="{{ $pName }}" 
                                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:rotate-3">
                        </a>
                    </div>
                    
                    <h3 class="font-bold text-dark text-lg mb-1 group-hover:text-primary transition-colors truncate px-2">{{ $pName }}</h3>
                    <p class="text-gray-500">₹{{ number_format($pPrice) }}</p>
                </div>
            @endforeach
        </div>
        
         <div class="text-center mt-8 md:hidden">
            <a href="#" class="inline-flex items-center text-dark font-bold hover:text-primary transition-colors">
                View Collection <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif
