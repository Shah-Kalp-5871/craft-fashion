<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sara Wilsson',
                'designation' => 'Designer',
                'message' => 'The quality of the jewelry is exceptional. I wore the necklace to a gala and received so many compliments!',
                'rating' => 5,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Matt Brandon',
                'designation' => 'Freelancer',
                'message' => 'Fast shipping and beautiful packaging. The ring fits perfectly and looks even better in person.',
                'rating' => 5,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'John Larson',
                'designation' => 'Entrepreneur',
                'message' => 'I bought a bracelet for my wife and she loves it. Great customer service and amazing product.',
                'rating' => 5,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Emily Harison',
                'designation' => 'Store Owner',
                'message' => 'Truly exquisite craftsmanship. Will definitely be ordering again.',
                'rating' => 4,
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
