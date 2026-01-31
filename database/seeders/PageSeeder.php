<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'heading' => 'Our Story',
                'content' => '<h2>Our Story</h2><p>Welcome to APIQO Jewellery, where we craft timeless elegance with every piece. Founded with a passion for luxury and artistry, we believe that jewellery is more than just an accessory—it is an expression of your unique story.</p><p>Our journey began with a simple mission: to create exquisite, high-quality jewellery that accessible yet uncompromising on luxury. Each piece is meticulously designed and crafted by skilled artisans who pour their heart and soul into every detail.</p><h3>Sustainability</h3><p id="sustainability">We are committed to ethical sourcing and sustainable practices. We believe in beauty that doesn\'t cost the earth. That\'s why we use recycled metals wherever possible and ensure our gemstones are conflict-free.</p>',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'heading' => 'Contact Us',
                'content' => '<h2>Get in Touch</h2><p>We love hearing from our customers! Whether you have a question about our products, need assistance with an order, or just want to say hello, our team is here to help.</p><p><strong>Email:</strong> apiqojewellery@gmail.com<br><strong>Phone:</strong> +91 7490003767</p>',
            ],
            [
                'title' => 'Shipping & Delivery',
                'slug' => 'shipping-policy',
                'heading' => 'Shipping & Delivery',
                'content' => '<h2>Shipping Policy</h2><p>We offer free shipping on all orders over ₹1499. All orders are processed within 1-2 business days. Standard delivery takes 5-7 business days depending on your location.</p>',
            ],
            [
                'title' => 'Returns & Exchanges',
                'slug' => 'returns-exchanges',
                'heading' => 'Returns & Exchanges',
                'content' => '<h2>Easy Returns</h2><p>We want you to love your purchase. If for any reason you are not satisfied, you may return the item within 7 days of delivery for a full refund or exchange, provided it is in its original condition.</p>',
            ],
            [
                'title' => 'Size Guide',
                'slug' => 'size-guide',
                'heading' => 'Size Guide',
                'content' => '<h2>Find Your Perfect Fit</h2><p>Use our comprehensive size guide to find the perfect fit for rings, necklaces, and bracelets. We recommend measuring a piece of jewellery you already own for comparison.</p>',
            ],
            [
                'title' => 'Care Instructions',
                'slug' => 'care-instructions',
                'heading' => 'Care Instructions',
                'content' => '<h2>Caring for Your Jewellery</h2><p>To keep your APIQO jewellery looking its best, avoid contact with perfumes, lotions, and harsh chemicals. Store your pieces in a cool, dry place, preferably in the provided pouch or box.</p>',
            ],
            [
                'title' => 'FAQs',
                'slug' => 'faq',
                'heading' => 'Frequently Asked Questions',
                'content' => '<h2>FAQs</h2><p><strong>Q: Is your jewellery authentic?</strong><br>A: Yes, all our pieces are certified authentic and crafted with high-quality materials.</p><p><strong>Q: Do you ship internationally?</strong><br>A: Currently, we only ship within India.</p>',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'heading' => 'Privacy Policy',
                'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This policy outlines how we collect, use, and protect your personal information.</p>',
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'heading' => 'Terms & Conditions',
                'content' => '<h2>Terms & Conditions</h2><p>By using our website, you agree to comply with and be bound by the following terms and conditions of use.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'meta_title' => $page['title'] . ' - APIQO Jewellery',
                    'meta_description' => substr(strip_tags($page['content']), 0, 160),
                    'is_active' => true,
                ]
            );
        }
    }
}
