<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        $reviewsData = [
            [
                'rating' => 5,
                'review' => 'Absolutely stunning! The craftsmanship is top-notch and it looks even better in person.',
            ],
            [
                'rating' => 4,
                'review' => 'Great quality product. The delivery was a bit slow, but worth the wait.',
            ],
            [
                'rating' => 5,
                'review' => 'I am in love with this! Fits perfectly and fails deep into the luxury category.',
            ],
            [
                'rating' => 5,
                'review' => 'Highly recommended. The attention to detail is incredible.',
            ],
            [
                'rating' => 3,
                'review' => 'It is okay, but I expected the color to be a bit more vibrant.',
            ],
            [
                'rating' => 4,
                'review' => 'Very satisfied with my purchase. Will definitely buy again.',
            ],
            [
                'rating' => 5,
                'review' => 'Exceeded my expectations! A true masterpiece.',
            ],
            [
                'rating' => 4,
                'review' => 'Good value for money. Looks very elegant.',
            ],
            [
                'rating' => 5,
                'review' => 'Perfect gift for my wife. She absolutely adored it.',
            ],
             [
                'rating' => 4,
                'review' => 'The texture and feel of the material are premium. Satisfied customer.',
            ],
        ];

        $names = [
            'Aarav Patel', 'Vihaan Sharma', 'Aditya Verma', 'Sai Iyer', 'Reyansh Gupta',
            'Saanvi Rao', 'Ananya Singh', 'Diya Malhotra', 'Ishita Joshi', 'Zara Khan',
            'Arjun Nair', 'Rohan Mehta', 'Kabir Das', 'Meera Reddy', 'Naira Kapoor'
        ];

        if ($products->count() > 0) {
            foreach ($products as $product) {
                // Add random number of reviews (2 to 5) for each product
                $numberOfReviews = rand(2, 5);

                for ($i = 0; $i < $numberOfReviews; $i++) {
                    $randomReview = $reviewsData[array_rand($reviewsData)];
                    
                    Review::create([
                        'product_id' => $product->id,
                        'user_name' => $names[array_rand($names)],
                        'user_icon' => null, 
                        'rating' => $randomReview['rating'],
                        'review' => $randomReview['review'],
                        'status' => true,
                    ]);
                }
            }
        }
    }
}
