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

        <!-- Style 7: 2 Column Featured Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($section['products'] as $product)
                @php
                    $pName = is_array($product) ? $product['name'] : $product->name;
                    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
                    $pPrice = is_array($product) ? $product['price'] : $product->price;
                    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
                @endphp
                <div class="group relative overflow-hidden rounded-xl aspect-[16/9] shadow-md">
                     <a href="{{ route('customer.products.details', $pSlug) }}">
                        <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
                             alt="{{ $pName }}" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                    </a>
                    
                    <!-- Content Overlay -->
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition duration-300"></div>
                    
                    <div class="absolute bottom-0 left-0 p-8 w-full">
                        <div class="bg-white/95 backdrop-blur-sm p-6 rounded-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                             <h3 class="font-bold text-xl text-dark mb-2 line-clamp-1">{{ $pName }}</h3>
                             <div class="flex items-center justify-between">
                                  <span class="text-primary font-bold text-lg">₹{{ number_format($pPrice) }}</span>
                                  <a href="{{ route('customer.products.details', $pSlug) }}" class="text-sm font-bold uppercase tracking-wide border-b-2 border-primary pb-1">Shop Now</a>
                             </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
