<!-- Enhanced Footer with Luxury Animations -->
<style>
/* Footer Animation Keyframes */
@keyframes gradient-shift-footer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

@keyframes float-gentle {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}

@keyframes glow-pulse {
    0%, 100% { box-shadow: 0 0 15px rgba(139, 69, 19, 0.3); }
    50% { box-shadow: 0 0 25px rgba(139, 69, 19, 0.6); }
}

@keyframes shimmer-slide {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes sparkle-twinkle {
    0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
    50% { opacity: 1; transform: scale(1) rotate(180deg); }
}

@keyframes border-dance {
    0%, 100% { border-color: rgba(139, 69, 19, 0.3); }
    50% { border-color: rgba(139, 69, 19, 0.8); }
}

@keyframes float-rotate {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(5deg); }
}

@keyframes slide-in-footer {
    0% { opacity: 0; transform: translateY(50px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Footer Sections */
.footer-gradient {
    background: linear-gradient(135deg, #1a1a1a 0%, #080808ff 50%, #1a1a1a 100%);
    background-size: 200% 200%;
    animation: gradient-shift-footer 15s ease infinite;
    position: relative;
    overflow: hidden;
}

.footer-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(139, 69, 19, 0.5), transparent);
    animation: shimmer-slide 3s infinite;
}

/* Decorative Elements */
.footer-sparkle {
    position: absolute;
    width: 6px;
    height: 6px;
    background: linear-gradient(45deg, #fff, #D2B48C);
    border-radius: 50%;
    animation: sparkle-twinkle 3s infinite;
}

.footer-particle {
    position: absolute;
    width: 40px;
    height: 40px;
    background: radial-gradient(circle, rgba(139, 69, 19, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    animation: float-gentle 8s infinite ease-in-out;
    pointer-events: none;
}

/* Footer Links Enhanced */
.footer-link {
    position: relative;
    display: inline-block;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 0.25rem 0;
}

.footer-link::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #8B4513, #654321);
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.footer-link:hover::before {
    width: 100%;
}

.footer-link::after {
    content: '→';
    position: absolute;
    right: -20px;
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.footer-link:hover::after {
    opacity: 1;
    right: -25px;
}

.footer-link:hover {
    color: #D2B48C !important;
    transform: translateX(5px);
    letter-spacing: 0.5px;
}

/* Social Icons Enhanced */
.social-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.1), rgba(101, 67, 33, 0.1));
    border: 2px solid rgba(139, 69, 19, 0.3);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.social-icon::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: radial-gradient(circle, rgba(139, 69, 19, 0.4), transparent);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.6s ease;
}

.social-icon:hover::before {
    width: 200%;
    height: 200%;
}

.social-icon:hover {
    transform: translateY(-8px) rotate(10deg) scale(1.15);
    border-color: #8B4513;
    box-shadow: 0 10px 30px rgba(139, 69, 19, 0.4);
    animation: glow-pulse 1.5s infinite;
}

.social-icon i {
    position: relative;
    z-index: 1;
    transition: all 0.4s ease;
}

.social-icon:hover i {
    color: #fff;
    transform: scale(1.2);
}

/* Newsletter Section */
.newsletter-container {
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.05), rgba(101, 67, 33, 0.05));
    border: 2px solid rgba(139, 69, 19, 0.2);
    border-radius: 20px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    transition: all 0.5s ease;
    animation: border-dance 3s infinite;
}

.newsletter-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(139, 69, 19, 0.1) 0%, transparent 70%);
    animation: float-rotate 10s infinite;
}

.newsletter-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
    border-color: rgba(139, 69, 19, 0.5);
}

.newsletter-input {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(139, 69, 19, 0.2);
    padding: 1rem 1.5rem;
    border-radius: 50px;
    color: #fff;
    transition: all 0.4s ease;
    width: 100%;
}

.newsletter-input:focus {
    outline: none;
    border-color: #8B4513;
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 20px rgba(139, 69, 19, 0.3);
    transform: scale(1.02);
}

.newsletter-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.newsletter-btn {
    background: linear-gradient(135deg, #8B4513, #0f0f0fff);
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 700;
    letter-spacing: 1px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.newsletter-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(15, 15, 15, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.6s ease;
}

.newsletter-btn:hover::before {
    width: 300%;
    height: 300%;
}

.newsletter-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 35px rgba(139, 69, 19, 0.5);
}

/* Logo Animation */
.footer-logo {
    position: relative;
    display: inline-block;
    transition: all 0.5s ease;
}

.footer-logo::after {
    content: '✨';
    position: absolute;
    top: -10px;
    right: -15px;
    font-size: 1.5rem;
    opacity: 0;
    transition: all 0.4s ease;
}

.footer-logo:hover::after {
    opacity: 1;
    transform: rotate(20deg);
    animation: sparkle-twinkle 1s infinite;
}

.footer-logo:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 0 20px rgba(139, 69, 19, 0.5));
}

/* Payment Icons */
.payment-icon {
    width: 50px;
    height: 32px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.payment-icon:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

/* Features Badge */
.feature-badge {
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.1), rgba(101, 67, 33, 0.1));
    border: 1px solid rgba(139, 69, 19, 0.3);
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.feature-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.6s ease;
}

.feature-badge:hover::before {
    left: 100%;
}

.feature-badge:hover {
    border-color: #8B4513;
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(139, 69, 19, 0.3);
}

/* Scroll to Top Button */
.scroll-top {
    position: fixed;
    bottom: 0.5rem;
    right: 1rem;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #8B4513, #654321);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transform: translateY(100px);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    box-shadow: 0 5px 20px rgba(139, 69, 19, 0.4);
}

.scroll-top.visible {
    opacity: 1;
    transform: translateY(0);
}

.scroll-top:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 10px 30px rgba(139, 69, 19, 0.6);
    animation: glow-pulse 1s infinite;
}

/* Copyright Wave Effect */
.copyright-line {
    position: relative;
    overflow: hidden;
}

.copyright-line::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #8B4513, transparent);
    animation: shimmer-slide 3s infinite;
}

/* Stagger Animations */
.footer-section {
    animation: slide-in-footer 0.8s ease-out forwards;
    opacity: 0;
}

.footer-section:nth-child(1) { animation-delay: 0.1s; }
.footer-section:nth-child(2) { animation-delay: 0.2s; }
.footer-section:nth-child(3) { animation-delay: 0.3s; }
.footer-section:nth-child(4) { animation-delay: 0.4s; }

/* Decorative Corner */
.corner-decoration {
    position: absolute;
    width: 150px;
    height: 150px;
    opacity: 0.1;
    pointer-events: none;
}

.corner-decoration.top-left {
    top: 0;
    left: 0;
    background: radial-gradient(circle at top left, #8B4513, transparent);
}

.corner-decoration.bottom-right {
    bottom: 0;
    right: 0;
    background: radial-gradient(circle at bottom right, #654321, transparent);
}
</style>

<!-- Enhanced Luxury Footer -->
<footer class="footer-gradient text-white py-16 relative">
    <!-- Decorative Particles -->
    <div class="footer-particle" style="top: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="footer-particle" style="top: 60%; left: 80%; animation-delay: 2s;"></div>
    <div class="footer-particle" style="top: 30%; right: 10%; animation-delay: 4s;"></div>
    <div class="footer-particle" style="bottom: 20%; left: 70%; animation-delay: 1s;"></div>

    <!-- Sparkles -->
    <div class="footer-sparkle" style="top: 15%; left: 20%; animation-delay: 0s;"></div>
    <div class="footer-sparkle" style="top: 70%; left: 60%; animation-delay: 1s;"></div>
    <div class="footer-sparkle" style="top: 40%; right: 15%; animation-delay: 2s;"></div>

    <!-- Corner Decorations -->
    <div class="corner-decoration top-left"></div>
    <div class="corner-decoration bottom-right"></div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <!-- Newsletter Section -->
        <div class="newsletter-container mb-16 relative z-10">
            <div class="text-center relative z-10">
                <h3 class="brand-title text-2xl mb-3 text-amber-300">
                    ✨ Join Our Exclusive Circle
                </h3>
                <p class="text-gray-300 mb-6 text-sm">
                    Be the first to discover new collections, exclusive offers, and luxury insights
                </p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                    <input
                        type="email"
                        placeholder="Enter your email address"
                        class="newsletter-input flex-1"
                    />
                    <button class="newsletter-btn text-white whitespace-nowrap">
                        SUBSCRIBE NOW
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-4">
                    <i class="fas fa-lock mr-1"></i>
                    Your privacy is protected. Unsubscribe anytime.
                </p>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Brand Column -->
            <div class="footer-section">
                <a href="{{ route('customer.home.index') }}" class="footer-logo">
                    <h3 class="brand-title text-3xl mb-4 text-amber-300 inline-block">
                        APIQO Jewellery
                    </h3>
                </a>
                <p class="text-sm text-gray-400 mb-6 leading-relaxed">
                    Crafting timeless elegance with every piece. Where luxury meets artistry.
                </p>
                <div class="flex gap-3 mb-6">
                    <a href="#" class="social-icon">
                        <i class="fab fa-instagram text-gray-300"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-facebook-f text-gray-300"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-pinterest-p text-gray-300"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-twitter text-gray-300"></i>
                    </a>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="feature-badge text-xs text-gray-300">
                        <i class="fas fa-shield-alt mr-1 text-amber-400"></i>
                        Certified Authentic
                    </span>
                    <span class="feature-badge text-xs text-gray-300">
                        <i class="fas fa-shipping-fast mr-1 text-amber-400"></i>
                        Free Shipping on orders over 1499
                    </span>
                </div>
            </div>

            <!-- Shop Column -->
            <div class="footer-section">
                <h4 class="font-bold mb-6 text-lg tracking-wider">SHOP</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('customer.products.list') }}" class="footer-link text-sm text-gray-400">New Arrivals</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.products.list') }}?filter=best_sellers" class="footer-link text-sm text-gray-400">Best Sellers</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.category.products', ['slug' => 'wedding-collection']) }}" class="footer-link text-sm text-gray-400">Wedding Collection</a>
                    </li>
                    
                   
                   
                </ul>
            </div>

            <!-- Customer Care Column -->
            <div class="footer-section">
                <h4 class="font-bold mb-6 text-lg tracking-wider">CUSTOMER CARE</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('customer.page.contact') }}" class="footer-link text-sm text-gray-400">Contact Us</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.page.shipping-policy') }}" class="footer-link text-sm text-gray-400">Shipping & Delivery</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.page.show', 'returns-exchanges') }}" class="footer-link text-sm text-gray-400">Returns & Exchanges</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.page.size-guide') }}" class="footer-link text-sm text-gray-400">Size Guide</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.page.show', 'care-instructions') }}" class="footer-link text-sm text-gray-400">Care Instructions</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.page.faq') }}" class="footer-link text-sm text-gray-400">FAQs</a>
                    </li>
                </ul>
            </div>

            <!-- About Column -->
            <div class="footer-section">
                <h4 class="font-bold mb-6 text-lg tracking-wider">ABOUT US</h4>
                <ul class="space-y-3 mb-6">
                    <li>
                        <a href="{{ route('customer.page.about') }}" class="footer-link text-sm text-gray-400">Our Story</a>
                    </li>
                   
                    <li>
                        <a href="{{ route('customer.page.about') }}#sustainability" class="footer-link text-sm text-gray-400">Sustainability</a>
                    </li>
                    
                  
                </ul>

                <!-- Contact Info -->
                <div class="space-y-3 text-sm text-gray-400">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-phone text-amber-400"></i>
                        <span>+91  7490003767</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-envelope text-amber-400"></i>
                        <span>apiqojewellery@gmail.com</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Methods & Features -->
        <div class="border-t border-gray-800 pt-8 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <div>
                    <p class="text-sm text-gray-400 mb-3 font-semibold">SECURE PAYMENT METHODS</p>
                    <div class="flex gap-3">
                        <div class="payment-icon">
                            <i class="fab fa-cc-visa text-xl text-gray-400"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-mastercard text-xl text-gray-400"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-amex text-xl text-gray-400"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-paypal text-xl text-gray-400"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-google-pay text-xl text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <span class="feature-badge text-xs text-gray-300">
                        <i class="fas fa-award mr-1 text-amber-400"></i>
                        Anti-Tarnish
                    </span>
                    <span class="feature-badge text-xs text-gray-300">
                        <i class="fas fa-tint mr-1 text-amber-400"></i>
                        Waterproof
                    </span>
                    <span class="feature-badge text-xs text-gray-300">
                        <i class="fas fa-leaf mr-1 text-amber-400"></i>
                        Hypoallergenic
                    </span>
                    <span class="feature-badge text-xs text-gray-300">
                        <i class="fas fa-redo mr-1 text-amber-400"></i>
                        Easy Returns
                    </span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright-line border-t border-gray-800 pt-8 text-center relative">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-400">
                    © {{ date('Y') }} <span class="text-amber-300 font-semibold">APIQO Jewellery</span>. All rights reserved.
                </p>
                <div class="flex gap-6 text-xs text-gray-400">
                    <a href="{{ route('customer.page.privacy') }}" class="hover:text-amber-300 transition">Privacy Policy</a>
                    <span>•</span>
                    <a href="{{ route('customer.page.terms') }}" class="hover:text-amber-300 transition">Terms & Conditions</a>
                    <span>•</span>
                    <a href="{{ route('customer.page.contact') }}" class="hover:text-amber-300 transition">Sitemap</a>
                </div>
            </div>
            
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up text-white text-xl"></i>
</button>
