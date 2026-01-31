@extends('customer.layouts.master')

@section('title', 'Contact Us | ' . config('constants.SITE_NAME'))
@section('description', "Get in touch with Craft Fashion for inquiries about our women's, girls' and kids' clothing collections in Yamuna Nagar.")

@section('content')
<!-- Modern Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-primary/10 via-white to-primary/5 overflow-hidden">
    <div class="absolute inset-0 bg-white/60 z-0"></div>
    <div class="absolute top-10 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-6">
                <i class="fas fa-headset mr-2"></i>
                We're Here to Help
            </div>

            <h1 class="text-5xl md:text-6xl font-bold font-playfair text-dark mb-6 leading-tight" data-aos="fade-up">
                Let's Start a <span class="text-primary">Conversation</span>
            </h1>

            <p class="text-xl text-secondary max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Have questions about our collections, need styling advice, or want to visit our boutique? We'd love to hear from you.
            </p>
        </div>
    </div>
</section>

<!-- Contact Information & Form -->
<section class="py-20 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Information -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 shadow-lg border border-primary/10 h-full">
                    <h2 class="text-3xl font-bold font-playfair text-dark mb-8">Get In Touch</h2>

                    <div class="space-y-8">
                        <!-- Location -->
                        <div class="flex items-start group">
                            <div class="bg-primary/10 p-4 rounded-2xl mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-map-marker-alt text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-dark mb-2">Visit Our Boutique</h3>
                                <p class="text-secondary mb-1">{{ config('constants.SITE_ADDRESS') }}</p>
                                <p class="text-secondary">Yamuna Nagar, {{ config('constants.SITE_PINCODE') }}</p>
                                <a href="#map-section"
                                    class="text-primary text-sm font-medium mt-2 inline-flex items-center hover:underline">
                                    <i class="fas fa-directions mr-1"></i> Get Directions
                                </a>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start group">
                            <div class="bg-primary/10 p-4 rounded-2xl mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-phone-alt text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-dark mb-2">Call Us</h3>
                                <a href="tel:{{ config('constants.SITE_PHONE') }}"
                                    class="text-secondary text-lg font-medium hover:text-primary transition">{{ config('constants.SITE_PHONE') }}</a>
                                <p class="text-secondary text-sm mt-1">Mon-Sat: 10AM-8PM | Sun: 11AM-6PM</p>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-start group">
                            <div class="bg-primary/10 p-4 rounded-2xl mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fab fa-whatsapp text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-dark mb-2">WhatsApp</h3>
                                <a href="{{ config('constants.WHATSAPP_LINK') }}"
                                    class="text-secondary hover:text-primary transition flex items-center">
                                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-medium mr-2">Quick Reply</span>
                                    Chat with us instantly
                                </a>
                                <p class="text-secondary text-sm mt-1">Get quick answers to your questions</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start group">
                            <div class="bg-primary/10 p-4 rounded-2xl mr-4 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-envelope text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-dark mb-2">Email Us</h3>
                                <a href="mailto:info@craftfashion.com"
                                    class="text-secondary hover:text-primary transition">info@craftfashion.com</a>
                                <p class="text-secondary text-sm mt-1">We'll respond within 24 hours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-dark mb-4">Follow Our Journey</h3>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="bg-gray-100 hover:bg-primary hover:text-white w-12 h-12 rounded-2xl flex items-center justify-center text-dark transition-all duration-300 hover:scale-110 hover:shadow-lg">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#"
                                class="bg-gray-100 hover:bg-pink-500 hover:text-white w-12 h-12 rounded-2xl flex items-center justify-center text-dark transition-all duration-300 hover:scale-110 hover:shadow-lg">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#"
                                class="bg-gray-100 hover:bg-red-500 hover:text-white w-12 h-12 rounded-2xl flex items-center justify-center text-dark transition-all duration-300 hover:scale-110 hover:shadow-lg">
                                <i class="fab fa-pinterest-p"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-primary/10 h-full">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-4">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Us a Message
                        </div>
                        <h2 class="text-3xl font-bold font-playfair text-dark mb-4">Let's Connect</h2>
                        <p class="text-secondary max-w-md mx-auto">Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>

                    <form action="#" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-dark font-semibold mb-2">Your Name *</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white"
                                    placeholder="Enter your full name">
                            </div>

                            <div>
                                <label for="email" class="block text-dark font-semibold mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white"
                                    placeholder="your.email@example.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-dark font-semibold mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white"
                                    placeholder="+91 12345 67890">
                            </div>

                            <div>
                                <label for="subject" class="block text-dark font-semibold mb-2">Subject</label>
                                <select id="subject" name="subject"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white">
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="product">Product Information</option>
                                    <option value="custom">Custom Order</option>
                                    <option value="tailoring">Tailoring Services</option>
                                    <option value="wholesale">Wholesale Inquiry</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-dark font-semibold mb-2">Your Message *</label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 bg-gray-50/50 focus:bg-white resize-none"
                                placeholder="Tell us how we can help you..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-primary text-white px-8 py-4 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 flex items-center justify-center group">
                            <span>Send Message</span>
                            <i class="fas fa-paper-plane ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </button>

                        <p class="text-center text-secondary text-sm">
                            * Required fields. We respect your privacy and will never share your information.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Action Cards -->
<section class="py-16 bg-gradient-to-br from-gray-50 to-primary/5 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-playfair text-dark mb-4" data-aos="fade-up">Quick Connect Options</h2>
            <p class="text-xl text-secondary max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Choose the most convenient way to reach us</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- WhatsApp Card -->
            <div data-aos="fade-up"
                class="bg-white rounded-3xl p-8 shadow-lg border border-green-200 text-center group hover:shadow-xl transition-all duration-300">
                <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                    <i class="fab fa-whatsapp text-green-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Instant WhatsApp</h3>
                <p class="text-secondary mb-6">Get quick answers to your questions with instant messaging</p>
                <a href="{{ config('constants.WHATSAPP_LINK') }}"
                    class="bg-green-500 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-green-600 inline-flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Start Chat
                </a>
            </div>

            <!-- Call Card -->
            <div data-aos="fade-up" data-aos-delay="100"
                class="bg-white rounded-3xl p-8 shadow-lg border border-primary/20 text-center group hover:shadow-xl transition-all duration-300">
                <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-phone text-primary text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Direct Call</h3>
                <p class="text-secondary mb-6">Speak directly with our team for personalized assistance</p>
                <a href="tel:{{ config('constants.SITE_PHONE') }}"
                    class="bg-primary text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary/90 inline-flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    Call Now
                </a>
            </div>

            <!-- Visit Card -->
            <div data-aos="fade-up" data-aos-delay="200"
                class="bg-white rounded-3xl p-8 shadow-lg border border-primary/20 text-center group hover:shadow-xl transition-all duration-300">
                <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-store text-primary text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Visit Store</h3>
                <p class="text-secondary mb-6">Experience our collection in person with personal styling</p>
                <a href="#map-section"
                    class="bg-primary text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary/90 inline-flex items-center">
                    <i class="fas fa-directions mr-2"></i>
                    Get Directions
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section id="map-section" class="py-16 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Find Our Store
            </div>
            <h2 class="text-4xl font-bold font-playfair text-dark mb-4" data-aos="fade-up">Visit Our Boutique</h2>
            <p class="text-xl text-secondary max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Come experience our collection in person at our Yamuna Nagar location</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Store Info -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-3xl p-8 h-full border border-primary/20">
                    <h3 class="text-2xl font-bold font-playfair text-dark mb-6">Store Details</h3>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-white p-3 rounded-xl mr-4 shadow-sm">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-dark mb-1">Opening Hours</h4>
                                <p class="text-secondary">Monday - Saturday: 10:00 AM - 8:00 PM</p>
                                <p class="text-secondary">Sunday: 11:00 AM - 6:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white p-3 rounded-xl mr-4 shadow-sm">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-dark mb-1">Address</h4>
                                <p class="text-secondary">{{ config('constants.SITE_ADDRESS') }}</p>
                                <p class="text-secondary">Yamuna Nagar, {{ config('constants.SITE_PINCODE') }}</p>
                                <p class="text-primary text-sm font-medium mt-1">Landmark: Near Huda Market</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white p-3 rounded-xl mr-4 shadow-sm">
                                <i class="fas fa-car text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-dark mb-1">Parking</h4>
                                <p class="text-secondary">Ample parking available nearby</p>
                                <p class="text-secondary text-sm">Convenient location with easy access</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 mt-6 border border-primary/20">
                            <h4 class="font-bold text-dark mb-3">Store Services</h4>
                            <ul class="space-y-2 text-secondary">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Free Home Trial Available
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Personal Styling Consultation
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Custom Tailoring Services
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-primary mr-2 text-sm"></i>
                                    Size Alterations Available
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="rounded-3xl overflow-hidden shadow-2xl border border-primary/10">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3452.769921153434!2d77.2994153151155!3d30.0720729818748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fb5b5dac5e5a5%3A0x4a5a5a5a5a5a5a5a!2sHUDA%20Sector%2017%2C%20Yamuna%20Nagar%2C%20Haryana%20135003!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin"
                        width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
                        class="rounded-3xl"></iframe>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <a href="{{ config('constants.WHATSAPP_LINK') }}?text=I'm planning to visit your store. What's the best time to come?" 
                       class="bg-primary text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary/90 flex items-center justify-center text-center">
                        <i class="fab fa-whatsapp mr-3 text-xl"></i>
                        Confirm Visit Timing
                    </a>
                    <a href="tel:{{ config('constants.SITE_PHONE') }}" 
                       class="bg-white text-dark border border-primary px-6 py-4 rounded-2xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary hover:text-white flex items-center justify-center text-center">
                        <i class="fas fa-phone mr-3 text-xl"></i>
                        Call for Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Final CTA -->
<section class="py-20 bg-gradient-to-r from-dark to-primary text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/2 translate-y-1/2"></div>

    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 data-aos="fade-up" class="text-4xl md:text-6xl font-bold font-playfair mb-6">
            Ready to Transform Your Style?
        </h2>
        <p data-aos="fade-up" data-aos-delay="100" class="text-xl md:text-2xl mb-8 text-white/90 max-w-3xl mx-auto">
            Whether you visit us in store or connect online, we're here to help you discover your perfect look
        </p>

        <div data-aos="fade-up" data-aos-delay="200" class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="{{ route('customer.products.list') }}"
                class="group bg-white text-dark px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl inline-flex items-center justify-center">
                <span>Browse Collection</span>
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ config('constants.WHATSAPP_LINK') }}"
                class="group bg-primary text-white px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl inline-flex items-center justify-center border-2 border-primary/30">
                <i class="fab fa-whatsapp mr-3 text-xl"></i>
                <span>Get Style Advice</span>
            </a>
        </div>
    </div>
</section>
@endsection


@push('scripts')
<script>
    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.querySelector('form');
        
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const name = formData.get('name');
                const email = formData.get('email');
                const phone = formData.get('phone');
                const subject = formData.get('subject');
                const message = formData.get('message');
                
                // Create WhatsApp message
                const whatsappMessage = `New Contact Form Submission%0A%0A` +
                    `Name: ${name}%0A` +
                    `Email: ${email}%0A` +
                    `Phone: ${phone}%0A` +
                    `Subject: ${subject}%0A` +
                    `Message: ${message}`;
                
                // Open WhatsApp with pre-filled message
                window.open(`{{ config('constants.WHATSAPP_LINK') }}&text=${whatsappMessage}`, '_blank');
                
                // Show success message
                alert('Thank you for your message! You are being redirected to WhatsApp to send your inquiry.');
                
                // Reset form
                this.reset();
            });
        }
        
        // Smooth scroll to map section
        const mapLinks = document.querySelectorAll('a[href="#map-section"]');
        mapLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const mapSection = document.getElementById('map-section');
                if (mapSection) {
                    mapSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });
</script>
@endpush