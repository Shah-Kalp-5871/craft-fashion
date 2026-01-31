<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\HomeSection;
use App\Models\Category;

class HomeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Banners
        Banner::create([
            'title' => 'Exquisite Bangles Collection',
            'subtitle' => 'Elegance in every curve. Up to 30% OFF.',
            'image' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=1200',
            'cta_text' => 'Shop Now',
            'cta_link' => '/category/bangles',
            'sort_order' => 1,
            'status' => true,
        ]);

        Banner::create([
            'title' => 'Stunning Ring Designs',
            'subtitle' => 'Timeless beauty for your fingers.',
            'image' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=1200',
            'cta_text' => 'Explore',
            'cta_link' => '/category/rings',
            'sort_order' => 2,
            'status' => true,
        ]);

        // 2. Create Home Sections (Demo assuming categories exist)
        $categories = Category::limit(6)->get();
        $styles = ['style_1', 'style_2', 'style_3', 'style_4', 'style_5', 'style_6'];

        foreach ($categories as $index => $category) {
            HomeSection::create([
                'title' => $category->name . ' Collection',
                'subtitle' => 'Explore our premium ' . strtolower($category->name),
                'type' => 'category',
                'category_id' => $category->id,
                'style' => $styles[$index % count($styles)],
                'sort_order' => $index + 1,
                'status' => true,
            ]);
        }
    }
}
