@extends('customer.layouts.master')

@section('title', 'Privacy Policy | ' . config('constants.SITE_NAME'))
@section('description', 'Read Craft Fashion\'s privacy policy to understand how we collect, use, and protect your personal information.')

@section('content')
<!-- Policy Hero -->
<section class="bg-dark text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold font-playfair mb-4" data-aos="fade-up">Privacy Policy</h1>
        <p class="text-xl" data-aos="fade-up" data-aos-delay="100">Last updated: {{ date('F j, Y') }}</p>
    </div>
</section>

<!-- Policy Content -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="prose prose-lg text-secondary" data-aos="fade-up">
            <p>At Craft Fashion, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or interact with us.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Information We Collect</h2>
            <p>We may collect personal information that you voluntarily provide to us when you:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Place an order on our website</li>
                <li>Create an account with us</li>
                <li>Contact us through our website, phone, or WhatsApp</li>
                <li>Subscribe to our newsletter</li>
                <li>Visit our physical store</li>
                <li>Interact with us on social media</li>
            </ul>
            <p class="mt-4">The types of personal information we may collect include:</p>
            <ul class="list-disc pl-5 space-y-2 mt-2">
                <li>Name and contact details (email, phone number, address)</li>
                <li>Payment information (processed securely through our payment gateway)</li>
                <li>Order history and preferences</li>
                <li>Communication preferences</li>
                <li>Any other information you choose to provide</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">How We Use Your Information</h2>
            <p>We may use the information we collect to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Process and fulfill your orders</li>
                <li>Create and manage your account</li>
                <li>Respond to your inquiries and provide customer service</li>
                <li>Send you information about our products, promotions, and updates</li>
                <li>Personalize your shopping experience</li>
                <li>Improve our website and customer experience</li>
                <li>Detect and prevent fraud</li>
                <li>Comply with legal obligations</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Data Security</h2>
            <p>We implement appropriate technical and organizational measures to protect the security of your personal information. This includes:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Secure Socket Layer (SSL) encryption for data transmission</li>
                <li>Regular security assessments</li>
                <li>Access controls and authentication procedures</li>
                <li>Secure payment processing through trusted partners</li>
            </ul>
            <p class="mt-4">However, please note that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Cookies and Tracking Technologies</h2>
            <p>Our website uses cookies and similar tracking technologies to enhance your browsing experience. Cookies are small files stored on your device that help us:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Remember your preferences</li>
                <li>Understand how you use our website</li>
                <li>Improve our services</li>
                <li>Provide personalized content</li>
            </ul>
            <p class="mt-4">You can control cookies through your browser settings. However, disabling cookies may limit your ability to use certain features of our website.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Sharing Your Information</h2>
            <p>We do not sell, trade, or otherwise transfer your personal information to third parties for marketing purposes. We may share your information in the following circumstances:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li><strong>Service Providers:</strong> With trusted third parties who assist us in operating our website, conducting business, or servicing you (e.g., payment processors, shipping companies)</li>
                <li><strong>Legal Requirements:</strong> To comply with legal obligations, enforce our site policies, or protect ours or others' rights, property, or safety</li>
                <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                <li><strong>With Your Consent:</strong> When you explicitly agree to the sharing of your information</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Your Rights</h2>
            <p>You have the right to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Access the personal information we hold about you</li>
                <li>Request correction of inaccurate or incomplete information</li>
                <li>Request deletion of your personal information (subject to legal requirements)</li>
                <li>Object to or restrict the processing of your information</li>
                <li>Withdraw consent at any time (where processing is based on consent)</li>
                <li>Opt-out of marketing communications</li>
                <li>Request data portability</li>
            </ul>
            <p class="mt-4">To exercise any of these rights, please contact us using the details provided below.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Third-Party Links</h2>
            <p>Our website may contain links to third-party websites. These third-party sites have separate and independent privacy policies. We have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Children's Privacy</h2>
            <p>Our website is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe that your child has provided us with personal information, please contact us immediately.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Data Retention</h2>
            <p>We will retain your personal information only for as long as is necessary for the purposes set out in this Privacy Policy, or as required to comply with our legal obligations, resolve disputes, and enforce our agreements.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Changes to This Policy</h2>
            <p>We may update this Privacy Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify you of any material changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>
            <p class="mt-2">We encourage you to review this Privacy Policy periodically to stay informed about how we are protecting your information.</p>
            
            <h2 class="text-2xl font-bold text-dark mt-8 mb-4">Contact Us</h2>
            <p>If you have any questions, concerns, or requests regarding this Privacy Policy or our privacy practices, please contact us:</p>
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
            <p class="mt-6">Thank you for trusting Craft Fashion with your personal information. We are committed to protecting your privacy and providing you with a secure shopping experience.</p>
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