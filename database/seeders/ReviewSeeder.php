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
        $names = ['Alice Smith', 'Bob Jones', 'Charlie Brown', 'Diana Prince', 'Evan Wright'];
        $reviews = [
            'Absolutely love this piece! The quality is amazing.',
            'Beautiful craftsmanship, exactly what I was looking for.',
            'Fast shipping and great packaging. Highly recommend!',
            'The diamonds sparkle so much in person. Very happy.',
            'Good value for money, though I wish the chain was slightly longer.',
        ];

        foreach ($products as $product) {
            for ($i = 0; $i < 5; $i++) {
                Review::create([
                    'product_id' => $product->id,
                    'user_name' => $names[array_rand($names)],
                    'user_icon' => null, 
                    'rating' => rand(3, 5),
                    'review' => $reviews[array_rand($reviews)],
                    'status' => true,
                ]);
            }
        }
    }
}
