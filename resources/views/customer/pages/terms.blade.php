@extends('customer.layouts.master')

@section('title', 'Terms & Conditions | ' . config('constants.SITE_NAME'))
@section('description', "Review Craft Fashion's terms and conditions for website usage, product purchases, and customer interactions.")

@section('content')
<!-- Terms Hero -->
<section class="bg-dark text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold font-playfair mb-4" data-aos="fade-up">Terms & Conditions</h1>
        <p class="text-xl" data-aos="fade-up" data-aos-delay="100">Last updated: {{ date('F j, Y') }}</p>
    </div>
</section>

<!-- Terms Content -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="prose prose-lg text-secondary" data-aos="fade-up">
            <p>Welcome to Craft Fashion. These Terms and Conditions outline the rules and regulations for the use of our website, mobile applications, and services. By accessing or using our website, you accept these terms in full. If you disagree with any part of these terms, you must not use our website.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">1. Acceptance of Terms</h2>
            <p>By accessing, browsing, or using our website, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. These terms apply to all visitors, users, and others who access or use our service.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">2. Eligibility</h2>
            <p>You must be at least 18 years of age to use our website or place an order. By using this website, you represent and warrant that you are at least 18 years old. If you are under 18, you may use our website only with involvement of a parent or guardian.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">3. Account Registration</h2>
            <p>To access certain features of our website, you may be required to create an account. You agree to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Provide accurate, current, and complete information during registration</li>
                <li>Maintain and promptly update your account information</li>
                <li>Maintain the security of your password and accept all risks of unauthorized access</li>
                <li>Notify us immediately of any unauthorized use of your account</li>
                <li>Take responsibility for all activities that occur under your account</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">4. Intellectual Property Rights</h2>
            <p>All content on this website, including but not limited to text, graphics, logos, images, audio clips, digital downloads, data compilations, and software, is the property of Craft Fashion or its content suppliers and is protected by international copyright laws.</p>
            <p>You may not:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Reproduce, duplicate, copy, sell, resell, or exploit any portion of the website without express written permission</li>
                <li>Use any data mining, robots, or similar data gathering and extraction tools</li>
                <li>Modify, adapt, translate, reverse engineer, decompile, or disassemble any portion of the website</li>
                <li>Remove any copyright or other proprietary notices from the website content</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">5. Products and Pricing</h2>
            <p><strong>Product Information:</strong> We make every effort to display as accurately as possible the colors, designs, sizes, and descriptions of our products. However, we cannot guarantee that your device's display will be accurate. Actual colors may vary.</p>
            <p><strong>Product Availability:</strong> All products are subject to availability. We reserve the right to discontinue any product at any time.</p>
            <p><strong>Pricing:</strong> All prices are in Indian Rupees (₹) and are subject to change without notice. We reserve the right to modify or discontinue products or services at any time without notice.</p>
            <p><strong>Errors:</strong> In the event of an error in price or product description, we reserve the right to cancel any orders placed for that product.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">6. Order Acceptance and Payment</h2>
            <p><strong>Order Confirmation:</strong> Your receipt of an order confirmation does not constitute our acceptance of your order. We reserve the right to accept or decline your order for any reason.</p>
            <p><strong>Payment Methods:</strong> We accept various payment methods as indicated on our website. All payments are processed securely through our payment gateway partners.</p>
            <p><strong>Payment Security:</strong> We implement reasonable security measures to protect your payment information. However, we cannot guarantee absolute security of data transmission over the Internet.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">7. Shipping and Delivery</h2>
            <p><strong>Shipping Times:</strong> Estimated delivery times are provided at checkout and are subject to change based on shipping carrier and destination.</p>
            <p><strong>Shipping Charges:</strong> Shipping charges are calculated based on weight, destination, and shipping method selected.</p>
            <p><strong>Risk of Loss:</strong> All items purchased from Craft Fashion are made pursuant to a shipment contract. The risk of loss and title for such items pass to you upon our delivery to the carrier.</p>
            <p><strong>International Shipping:</strong> For international orders, customers are responsible for any customs duties, taxes, or fees imposed by their country.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">8. Returns and Exchanges</h2>
            <p>Please refer to our Return Policy for detailed information about returns, exchanges, and refunds. We reserve the right to modify our return policy at any time.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">9. User Conduct</h2>
            <p>You agree not to use the website to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Violate any applicable laws or regulations</li>
                <li>Infringe upon the rights of others</li>
                <li>Interfere with or disrupt the website or servers</li>
                <li>Transmit any viruses, malware, or harmful code</li>
                <li>Collect or track personal information of others</li>
                <li>Impersonate any person or entity</li>
                <li>Engage in any fraudulent activity</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">10. Limitation of Liability</h2>
            <p>Craft Fashion shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Your access to or use of or inability to access or use the website</li>
                <li>Any conduct or content of any third party on the website</li>
                <li>Any content obtained from the website</li>
                <li>Unauthorized access, use, or alteration of your transmissions or content</li>
            </ul>
            <p>Our total liability to you for any claim arising out of or relating to these terms or our services shall not exceed the amount you paid us to use our services.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">11. Indemnification</h2>
            <p>You agree to defend, indemnify, and hold harmless Craft Fashion and its affiliates, officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, and expenses, including without limitation, reasonable attorney's fees and costs, arising out of or in any way connected with:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Your access to or use of the website</li>
                <li>Your violation of these Terms and Conditions</li>
                <li>Your violation of any third party right, including without limitation any intellectual property right</li>
                <li>Any claim that your content caused damage to a third party</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">12. Termination</h2>
            <p>We may terminate or suspend your account and access to the website immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach these Terms and Conditions.</p>
            <p>Upon termination, your right to use the website will immediately cease. All provisions of these Terms which by their nature should survive termination shall survive termination.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">13. Changes to Terms</h2>
            <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
            <p>By continuing to access or use our website after those revisions become effective, you agree to be bound by the revised terms.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">14. Governing Law and Dispute Resolution</h2>
            <p>These Terms shall be governed and construed in accordance with the laws of India, without regard to its conflict of law provisions.</p>
            <p>Any dispute arising out of or in connection with these Terms shall be subject to the exclusive jurisdiction of the courts located in Yamuna Nagar, Haryana, India.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">15. Entire Agreement</h2>
            <p>These Terms constitute the entire agreement between you and Craft Fashion regarding our website and supersede all prior agreements and understandings, whether written or oral.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">16. Severability</h2>
            <p>If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions will remain in effect.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">17. Waiver</h2>
            <p>Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">18. Contact Information</h2>
            <p>If you have any questions about these Terms and Conditions, please contact us:</p>
            <ul class="list-none pl-0 space-y-3 mt-4">
                <li class="flex items-start">
                    <i class="fas fa-phone-alt mt-1 mr-3 text-primary"></i>
                    <span>By phone: {{ config('constants.SITE_PHONE') }}</span>
                </li>
                <li class="flex items-start">
                    <i class="fab fa-whatsapp mt-1 mr-3 text-primary"></i>
                    <span>By WhatsApp: <a href="{{ config('constants.WHATSAPP_LINK') }}" class="text-primary hover:underline font-medium">Click to chat</a></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-envelope mt-1 mr-3 text-primary"></i>
                    <span>By email: <a href="mailto:info@craftfashion.com" class="text-primary hover:underline font-medium">info@craftfashion.com</a></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary"></i>
                    <span>By visiting our store: {{ config('constants.SITE_ADDRESS') }}, {{ config('constants.SITE_PINCODE') }}</span>
                </li>
            </ul>
            
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border-l-4 border-primary">
                <p class="font-semibold text-dark">Important Note:</p>
                <p class="mt-2">These Terms and Conditions are subject to change without notice. It is your responsibility to review these terms periodically for updates or changes. Your continued use of our website following the posting of changes will constitute your acceptance of such changes.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .prose ul li {
        margin-bottom: 0.5rem;
    }
    .prose a {
        text-decoration: none;
        font-weight: 500;
    }
    .prose a:hover {
        text-decoration: underline;
    }
</style>
@endpush