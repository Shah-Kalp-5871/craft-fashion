@if(isset($section) && count($section['products']) > 0)
<section class="py-16 bg-gray-50" data-aos="fade-up">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex justify-between items-end mb-12">
            <div class="max-w-xl">
                 @if($section['subtitle'])
                <span class="text-primary font-medium tracking-wider uppercase text-sm mb-2 block">{{ $section['subtitle'] }}</span>
                @endif
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-dark">{{ $section['title'] }}</h2>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="flex gap-2">
                <button class="swiper-prev-{{ $section['style'] }} w-10 h-10 rounded-full border border-dark/10 flex items-center justify-center hover:bg-dark hover:text-white transition-all">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="swiper-next-{{ $section['style'] }} w-10 h-10 rounded-full border border-dark/10 flex items-center justify-center hover:bg-dark hover:text-white transition-all">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Swiper -->
        <div class="swiper section-swiper-{{ $section['style'] }} overflow-visible">
            <div class="swiper-wrapper">
                @foreach($section['products'] as $product)
                 @php
                    $pName = is_array($product) ? $product['name'] : $product->name;
                    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
                    $pPrice = is_array($product) ? $product['price'] : $product->price;
                    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
                @endphp
                <div class="swiper-slide w-72">
                    <a href="{{ route('customer.products.details', $pSlug) }}" class="block group">
                        <div class="relative rounded-xl overflow-hidden mb-4 aspect-[3/4]">
                             <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
                                 alt="{{ $pName }}" 
                                 class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        </div>
                        <h3 class="font-bold text-dark group-hover:text-primary transition-colors truncate">{{ $pName }}</h3>
                        <p class="text-secondary">₹{{ number_format($pPrice) }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.section-swiper-{{ $section['style'] }}', {
                    slidesPerView: 1.5,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.swiper-next-{{ $section['style'] }}',
                        prevEl: '.swiper-prev-{{ $section['style'] }}',
                    },
                    breakpoints: {
                        640: { slidesPerView: 2.5 },
                        1024: { slidesPerView: 4.5 },
                    }
                });
            });
        </script>
    </div>
</section>
@endif
