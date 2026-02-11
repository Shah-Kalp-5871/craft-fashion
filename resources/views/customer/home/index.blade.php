@extends('customer.layouts.master')

@section('title', config('constants.SITE_NAME') . " | Premium Women's & Kids' Boutique")
@section('description', "Craft Fashion offers exquisite kurtis, tops, kurti-bottom sets, and boutique garments for women, girls, and kids in Yamuna Nagar.")

@section('styles')
<style>
    /* Hero Slider */
    .promo-slider {
        width: 100%;
        aspect-ratio: 1920 / 800;
        min-height: 500px; 
        max-height: 800px;
        position: relative;
        overflow: hidden;
    }

    .promo-slider .swiper {
        width: 100%;
        height: 100%;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.7);
    }

    .slide-content {
        position: absolute;
        top: 50%;
        left: 10%;
        transform: translateY(-50%);
        z-index: 10;
        color: white;
        max-width: 600px;
    }

    .slide-content h2 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        line-height: 1.1;
        font-family: 'Playfair Display', serif;
    }

    .slide-content p {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
    }

    .shop-btn {
        display: inline-block;
        padding: 1rem 2.5rem;
        background-color: #c98f83;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .shop-btn:hover {
        background-color: white;
        color: #393333;
        transform: translateY(-3px);
    }

    /* Collection Section Styles */
    .collection-section {
        padding: 4rem 5%;
    }

    .section-title {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .section-title h2 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        font-family: 'Playfair Display', serif;
        color: #393333;
    }

    .section-title p {
        color: #747471;
        max-width: 700px;
        margin: 0 auto;
    }

    .section-title::after {
        content: '';
        position: absolute;
        width: 100px;
        height: 3px;
        background-color: #c98f83;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Style 1: 3 Column Equal Grid */
    .style1-grid, .style7-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .style7-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .style1-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 4 / 5;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .style1-item:hover {
        transform: translateY(-10px);
    }

    .style1-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.6);
        transition: filter 0.3s ease;
    }

    .style1-item:hover img {
        filter: brightness(0.5);
    }

    .style1-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 90%;
        z-index: 2;
    }

    .style1-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .style1-btn {
        display: inline-block;
        padding: 0.8rem 1.8rem;
        background-color: #c98f83;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        text-transform: capitalize;
    }

    .style1-btn:hover {
        background-color: #b07d72;
        color: white;
        transform: scale(1.05);
    }

    /* Category Showcase Styles */
    .font-playfair { font-family: 'Playfair Display', serif; }
    .category-card { cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .category-card:hover { transform: translateY(-8px); }
    .image-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(156,39,176,0.3) 50%, transparent 100%); transition: opacity 0.5s; }
    .category-card:hover .image-overlay { opacity: 0.9; }
    .badge { backdrop-filter: blur(12px); animation: pulse 2s ease-in-out infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }
    .shine-effect { position: relative; overflow: hidden; }
    .shine-effect::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); transition: left 0.6s; z-index: 30; }
    .category-card:hover .shine-effect::before { left: 100%; }
    .cta-button { transition: all 0.3s ease; }
    .category-card:hover .cta-button { transform: scale(1.05); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }

    /* Stats Section */
    .stats-section { background-color: #393333; color: white; padding: 4rem 5%; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center; }
    .stat-item h3 { font-size: 2.5rem; font-weight: 800; color: #c98f83; margin-bottom: 0.5rem; }
    .stat-item p { text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; opacity: 0.8; }

    /* Testimonials */
    .testimonials { background-color: #f9f9f9; padding: 4rem 5%; }
    .testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 3rem; }
    .testimonial-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); position: relative; }
    .testimonial-card i.fa-quote-left { color: #c98f83; font-size: 2rem; opacity: 0.2; position: absolute; top: 1rem; left: 1rem; }
    
    /* Responsive Adjustments */
    @media (max-width: 1024px) {
        .promo-slider { height: 60vh; }
        .slide-content h2 { font-size: 2.5rem; }
        .stats-grid, .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .promo-slider { height: 50vh; }
        .slide-content h2 { font-size: 2rem; }
        .stats-grid, .testimonials-grid, .style1-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="home-page">

<!-- Hero Section -->
<section class="promo-slider">
    <div class="swiper main-swiper">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
            <div class="swiper-slide">
                <img src="{{ Str::startsWith($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                <div class="slide-content" data-aos="fade-up">
                    <h2>{{ $banner->title }}</h2>
                    <p>{{ $banner->subtitle }}</p>
                    @if($banner->cta_text)
                    <a href="{{ $banner->cta_link ?? route('customer.products.list') }}" class="shop-btn">{{ $banner->cta_text }}</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200" alt="Craft Fashion">
                <div class="slide-content">
                    <h2>Exquisite Boutique Wear</h2>
                    <p>Discover our unique collection of Kurtis & More</p>
                    <a href="{{ route('customer.products.list') }}" class="shop-btn">Shop Now</a>
                </div>
            </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>



<!-- Dynamic Home Sections -->
@if(isset($dynamicSections) && count($dynamicSections) > 0)
    @foreach($dynamicSections as $section)
        @if(isset($section['style']) && View::exists('customer.home.sections.' . $section['style']))
            @include('customer.home.sections.' . $section['style'], ['section' => $section])
        @endif
    @endforeach
@endif


<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div data-aos="fade-up" class="text-center p-6 bg-gray-50 rounded-xl">
                 <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4 text-primary text-2xl">
                    <i class="fas fa-truck"></i>
                </div>
                <h4 class="font-bold text-dark mb-2">Fastest Delivery</h4>
                <p class="text-sm text-gray-500">Express shipping across India</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="100" class="text-center p-6 bg-gray-50 rounded-xl">
                 <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4 text-primary text-2xl">
                    <i class="fas fa-undo"></i>
                </div>
                <h4 class="font-bold text-dark mb-2">Easy Returns</h4>
                <p class="text-sm text-gray-500">7-day hassle free returns</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="200" class="text-center p-6 bg-gray-50 rounded-xl">
                 <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4 text-primary text-2xl">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 class="font-bold text-dark mb-2">Secure Payment</h4>
                <p class="text-sm text-gray-500">100% secure transactions</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300" class="text-center p-6 bg-gray-50 rounded-xl">
                 <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4 text-primary text-2xl">
                    <i class="fas fa-tag"></i>
                </div>
                <h4 class="font-bold text-dark mb-2">Best Prices</h4>
                <p class="text-sm text-gray-500">Premium quality at boutique rates</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container mx-auto px-4">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="flex items-center justify-center gap-1">
                    <h3 class="stat-counter" data-target="2500">0</h3>
                    <span class="text-3xl font-bold text-primary">+</span>
                </div>
                <p>Happy Customers</p>
            </div>
            <div class="stat-item">
                <div class="flex items-center justify-center gap-1">
                    <h3 class="stat-counter" data-target="1200">0</h3>
                    <span class="text-3xl font-bold text-primary">+</span>
                </div>
                <p>Exquisite Designs</p>
            </div>
            <div class="stat-item">
                <div class="flex items-center justify-center gap-1">
                    <h3 class="stat-counter" data-target="8000">0</h3>
                    <span class="text-3xl font-bold text-primary">+</span>
                </div>
                <p>Orders Delivered</p>
            </div>
            <div class="stat-item">
                <div class="flex items-center justify-center gap-1">
                    <h3 class="stat-counter" data-target="99">0</h3>
                    <span class="text-3xl font-bold text-primary">%</span>
                </div>
                <p>Positive Feedback</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials">
    <div class="section-title">
        <h2>What Our Clients Say</h2>
        <p>Real stories from our beloved customers</p>
    </div>
    <div class="container mx-auto px-4">
        <div class="swiper testimonials-swiper pb-12">
            <div class="swiper-wrapper">
                @forelse($testimonials as $testimonial)
                <div class="swiper-slide h-auto">
                    <div class="testimonial-card h-full" data-aos="fade-up">
                        <i class="fas fa-quote-left"></i>
                        <p class="text-gray-600 italic mb-6">"{{ $testimonial->message }}"</p>
                        <div class="flex items-center gap-4 mt-auto">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold overflow-hidden">
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($testimonial->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-dark">{{ $testimonial->name }}</h4>
                                <p class="text-xs text-gray-400">{{ $testimonial->designation ?? 'Verified Buyer' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="swiper-slide">
                    <p class="text-center text-gray-400">Join our community of happy clients!</p>
                </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Hero Swiper
        const mainSwiper = new Swiper('.main-swiper', {
            loop: true,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
            effect: 'fade',
            fadeEffect: { crossFade: true }
        });

        // Initialize Testimonials Swiper
        const testimonialsSwiper = new Swiper('.testimonials-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });

        // Add to Cart Logic
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const variantId = this.getAttribute('data-variant-id');
                const originalContent = this.innerHTML;
                
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;

                try {
                    const response = await axios.post('{{ route("customer.cart.add") }}', {
                        variant_id: variantId,
                        quantity: 1
                    });

                    if (response.data.success) {
                        showToast(response.data.message || 'Added to cart!', 'success');
                        if (typeof updateCartCount === 'function') {
                            updateCartCount(response.data.cart_count);
                        }
                    } else {
                        showToast(response.data.message || 'Failed to add', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    showToast('Error adding to cart', 'error');
                } finally {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }
            });
        });
    });

    // Stats Counter Animation
    const counters = document.querySelectorAll('.stat-counter');
    const speed = 200;

    const startCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    };

    const statsSection = document.querySelector('.stats-section');
    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                startCounters();
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    if (statsSection) {
        observer.observe(statsSection);
    }
</script>
@endpush