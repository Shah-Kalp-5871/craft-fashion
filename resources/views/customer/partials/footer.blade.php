<footer class="bg-dark text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-2xl font-playfair font-bold mb-4">{{ config('constants.SITE_NAME') }}</h3>
                <p class="mb-4">Premium clothing for women, girls, and kids in Yamuna Nagar.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-white hover:text-primary transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white hover:text-primary transition"><i class="fab fa-instagram"></i></a>
                    <a href="{{ config('constants.WHATSAPP_LINK') }}" class="text-white hover:text-primary transition"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('customer.home.index') }}" class="hover:text-primary transition">Home</a></li>
                    <li><a href="{{ route('customer.page.about') }}" class="hover:text-primary transition">About Us</a></li>
                    <li><a href="{{ route('customer.products.list') }}" class="hover:text-primary transition">Products</a></li>
                    <li><a href="{{ route('customer.page.contact') }}" class="hover:text-primary transition">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                        <span>{{ config('constants.SITE_ADDRESS') . ', ' . config('constants.SITE_PINCODE') }}</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-2"></i>
                        <a href="tel:{{ config('constants.SITE_PHONE') }}" class="hover:text-primary transition">{{ config('constants.SITE_PHONE') }}</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        <a href="mailto:info@craftfashion.com" class="hover:text-primary transition">info@craftfashion.com</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-secondary mt-8 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ config('constants.SITE_NAME') }}. All Rights Reserved.</p>
            <div class="mt-2">
                <a href="{{ route('customer.page.privacy') }}" class="hover:text-primary transition">Privacy Policy</a> | 
                <a href="{{ route('customer.page.terms') }}" class="hover:text-primary transition">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>