<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClothingStoreSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // Clear existing data
        $this->truncateTables();

        // Seed in correct order
        $this->seedMediaReferences();
        $this->seedBrands();
        $this->seedCategories();
        $this->seedTags();
        $this->seedSpecificationGroups();
        $this->seedSpecifications();
        $this->seedAttributes();
        $this->seedAttributeValues();
        $this->seedTaxClasses();
        $this->seedSpecificationValues();
        $this->seedSpecGroupSpecs();
        $this->seedCategorySpecGroups();
        $this->seedCategoryAttributes();
        $this->seedProductsAndVariants();
        $this->seedProductRelationships();

        // Enable foreign key checks
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }

    private function truncateTables()
    {
        $tables = [
            'order_items',
            'cart_items',
            'wishlist_items',
            'reviews',
            'return_items',
            'inventory_transfers',
            'category_product',
            'product_tags',
            'related_products',
            'cross_sell_products',
            'upsell_products',
            'variant_attributes',
            'product_specifications',
            'variant_images',
            'product_variants',
            'products',
            'category_spec_groups',
            'spec_group_specs',
            'specification_values',
            'category_attributes',
            'attribute_values',
            'attributes',
            'specifications',
            'specification_groups',
            'tags',
            'categories',
            'brands',
            'category_hierarchies',
            'tax_classes',
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }

    private function seedMediaReferences()
    {
        // Media IDs 1-20 exist for product images
    }

    private function seedBrands()
    {
        $brands = [
            [
                'name' => 'Urban Threads',
                'slug' => 'urban-threads',
                'description' => 'Modern urban fashion for the contemporary lifestyle',
                'logo_id' => 1,
                'status' => 1,
                'sort_order' => 1,
                'meta_title' => 'Urban Threads - Modern Fashion Brand',
                'meta_description' => 'Contemporary urban fashion for everyday style',
                'meta_keywords' => 'urban fashion, modern clothing, streetwear',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Classic Stitch',
                'slug' => 'classic-stitch',
                'description' => 'Timeless classic clothing with premium craftsmanship',
                'logo_id' => 2,
                'status' => 1,
                'sort_order' => 2,
                'meta_title' => 'Classic Stitch - Timeless Fashion',
                'meta_description' => 'Classic clothing with exceptional quality and timeless designs',
                'meta_keywords' => 'classic fashion, timeless clothing, premium quality',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trendy Styles',
                'slug' => 'trendy-styles',
                'description' => 'Latest fashion trends and seasonal collections',
                'logo_id' => 3,
                'status' => 1,
                'sort_order' => 3,
                'meta_title' => 'Trendy Styles - Latest Fashion Trends',
                'meta_description' => 'Stay updated with the latest fashion trends and seasonal collections',
                'meta_keywords' => 'trendy fashion, latest styles, seasonal collections',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Comfort Wear',
                'slug' => 'comfort-wear',
                'description' => 'Premium comfortable clothing for everyday wear',
                'logo_id' => 4,
                'status' => 1,
                'sort_order' => 4,
                'meta_title' => 'Comfort Wear - Premium Comfort Clothing',
                'meta_description' => 'High-quality comfortable clothing for daily activities',
                'meta_keywords' => 'comfort clothing, daily wear, casual wear',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }

    private function seedCategories()
    {
        $categories = [
            [
                'parent_id' => null,
                'name' => "Women's Wear",
                'slug' => 'womens-wear',
                'description' => 'Trendy and comfortable women\'s clothing collection for all occasions',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 1,
                'image_id' => 5,
                'meta_title' => "Women's Clothing - Latest Fashion Trends",
                'meta_description' => 'Discover the latest women\'s fashion trends including dresses, tops, bottoms and more',
                'meta_keywords' => 'womens clothing, fashion, dresses, tops, skirts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => "Men's Wear",
                'slug' => 'mens-wear',
                'description' => 'Stylish men\'s clothing collection for casual and formal occasions',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 2,
                'image_id' => 6,
                'meta_title' => "Men's Clothing - Modern Men's Fashion",
                'meta_description' => 'Explore stylish men\'s clothing including shirts, trousers, jackets and accessories',
                'meta_keywords' => 'mens clothing, shirts, trousers, jackets, mens fashion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => "Girl's Wear",
                'slug' => 'girls-wear',
                'description' => 'Cute and trendy clothing for girls of all ages',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 3,
                'image_id' => 7,
                'meta_title' => "Girl's Clothing - Trendy Kids Fashion",
                'meta_description' => 'Adorable and trendy clothing collections for girls including dresses, tops and sets',
                'meta_keywords' => 'girls clothing, kids fashion, dresses, tops, girl clothes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => "Kid's Wear",
                'slug' => 'kids-wear',
                'description' => 'Comfortable and stylish clothing for kids and toddlers',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 4,
                'image_id' => 8,
                'meta_title' => "Kid's Clothing - Comfortable Kids Wear",
                'meta_description' => 'Comfortable and durable clothing for kids including sets, rompers and casual wear',
                'meta_keywords' => 'kids clothing, toddler clothes, baby clothes, kids wear',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Rugs',
                'slug' => 'rugs',
                'description' => 'Beautiful and high-quality rugs for home decor',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 5,
                'image_id' => 9,
                'meta_title' => 'Rugs Collection - Home Decor Carpets',
                'meta_description' => 'Discover beautiful rugs and carpets for home decoration including Persian, modern and traditional styles',
                'meta_keywords' => 'rugs, carpets, home decor, floor rugs, Persian rugs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);

        // Create category hierarchies
        $this->createCategoryHierarchies();
    }

    private function createCategoryHierarchies()
    {
        $categories = DB::table('categories')->get();
        $hierarchies = [];

        foreach ($categories as $category) {
            $hierarchies[] = [
                'ancestor_id' => $category->id,
                'descendant_id' => $category->id,
                'depth' => 0,
            ];
        }

        DB::table('category_hierarchies')->insert($hierarchies);
    }

    private function seedTags()
    {
        $tags = [
            ['name' => 'New Arrival', 'slug' => 'new-arrival', 'status' => 1],
            ['name' => 'Best Seller', 'slug' => 'best-seller', 'status' => 1],
            ['name' => 'Summer Collection', 'slug' => 'summer-collection', 'status' => 1],
            ['name' => 'Winter Wear', 'slug' => 'winter-wear', 'status' => 1],
            ['name' => 'Casual', 'slug' => 'casual', 'status' => 1],
            ['name' => 'Formal', 'slug' => 'formal', 'status' => 1],
            ['name' => 'Cotton', 'slug' => 'cotton', 'status' => 1],
            ['name' => 'Polyester', 'slug' => 'polyester', 'status' => 1],
            ['name' => 'Linen', 'slug' => 'linen', 'status' => 1],
            ['name' => 'Silk', 'slug' => 'silk', 'status' => 1],
            ['name' => 'Eco-Friendly', 'slug' => 'eco-friendly', 'status' => 1],
            ['name' => 'Machine Washable', 'slug' => 'machine-washable', 'status' => 1],
            ['name' => 'Hand Wash Only', 'slug' => 'hand-wash-only', 'status' => 1],
            ['name' => 'Colorfast', 'slug' => 'colorfast', 'status' => 1],
            ['name' => 'Wrinkle Resistant', 'slug' => 'wrinkle-resistant', 'status' => 1],
            ['name' => 'Plus Size', 'slug' => 'plus-size', 'status' => 1],
            ['name' => 'Petite', 'slug' => 'petite', 'status' => 1],
            ['name' => 'Trendy', 'slug' => 'trendy', 'status' => 1],
            ['name' => 'Traditional', 'slug' => 'traditional', 'status' => 1],
            ['name' => 'Handmade', 'slug' => 'handmade', 'status' => 1],
        ];

        foreach ($tags as $tag) {
            $tag['created_at'] = now();
            $tag['updated_at'] = now();
            DB::table('tags')->insert($tag);
        }
    }

    private function seedSpecificationGroups()
    {
        $groups = [
            ['name' => 'Material & Fabric', 'sort_order' => 1, 'status' => 1],
            ['name' => 'Size & Fit', 'sort_order' => 2, 'status' => 1],
            ['name' => 'Care Instructions', 'sort_order' => 3, 'status' => 1],
            ['name' => 'Additional Details', 'sort_order' => 4, 'status' => 1],
        ];

        foreach ($groups as $group) {
            $group['created_at'] = now();
            $group['updated_at'] = now();
            DB::table('specification_groups')->insert($group);
        }
    }

    private function seedSpecifications()
    {
        $specifications = [
            [
                'name' => 'Main Fabric',
                'code' => 'main_fabric',
                'input_type' => 'select',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fabric Composition',
                'code' => 'fabric_composition',
                'input_type' => 'text',
                'is_required' => 1,
                'is_filterable' => 0,
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fabric Weight',
                'code' => 'fabric_weight',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pattern',
                'code' => 'pattern',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Color Family',
                'code' => 'color_family',
                'input_type' => 'select',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 5,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gender',
                'code' => 'gender',
                'input_type' => 'select',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 6,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Age Group',
                'code' => 'age_group',
                'input_type' => 'select',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 7,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Care Instructions',
                'code' => 'care_instructions',
                'input_type' => 'textarea',
                'is_required' => 1,
                'is_filterable' => 0,
                'sort_order' => 8,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Washing Temperature',
                'code' => 'washing_temperature',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 9,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ironing Instructions',
                'code' => 'ironing_instructions',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 0,
                'sort_order' => 10,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Origin',
                'code' => 'origin',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 11,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rug Type',
                'code' => 'rug_type',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 12,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rug Material',
                'code' => 'rug_material',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 13,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('specifications')->insert($specifications);
    }

    private function seedSpecificationValues()
    {
        $specs = DB::table('specifications')->pluck('id', 'code');
        $values = [];

        // Main Fabric values
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Cotton', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Polyester', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Linen', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Silk', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Wool', 'sort_order' => 5, 'status' => 1];
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Denim', 'sort_order' => 6, 'status' => 1];
        $values[] = ['specification_id' => $specs['main_fabric'], 'value' => 'Velvet', 'sort_order' => 7, 'status' => 1];

        // Fabric Weight values
        $values[] = ['specification_id' => $specs['fabric_weight'], 'value' => 'Lightweight', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['fabric_weight'], 'value' => 'Medium Weight', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['fabric_weight'], 'value' => 'Heavy Weight', 'sort_order' => 3, 'status' => 1];

        // Pattern values
        $values[] = ['specification_id' => $specs['pattern'], 'value' => 'Solid', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['pattern'], 'value' => 'Striped', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['pattern'], 'value' => 'Printed', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['pattern'], 'value' => 'Floral', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['pattern'], 'value' => 'Geometric', 'sort_order' => 5, 'status' => 1];
        $values[] = ['specification_id' => $specs['pattern'], 'value' => 'Checkered', 'sort_order' => 6, 'status' => 1];

        // Color Family values
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Black', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'White', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Blue', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Red', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Green', 'sort_order' => 5, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Yellow', 'sort_order' => 6, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Pink', 'sort_order' => 7, 'status' => 1];
        $values[] = ['specification_id' => $specs['color_family'], 'value' => 'Multi Color', 'sort_order' => 8, 'status' => 1];

        // Gender values
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Women', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Men', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Girls', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Boys', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Unisex', 'sort_order' => 5, 'status' => 1];

        // Age Group values
        $values[] = ['specification_id' => $specs['age_group'], 'value' => 'Adult', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['age_group'], 'value' => 'Teen', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['age_group'], 'value' => 'Kids (5-12 years)', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['age_group'], 'value' => 'Toddler (2-4 years)', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['age_group'], 'value' => 'Infant (0-2 years)', 'sort_order' => 5, 'status' => 1];

        // Washing Temperature values
        $values[] = ['specification_id' => $specs['washing_temperature'], 'value' => 'Cold (30°C)', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['washing_temperature'], 'value' => 'Warm (40°C)', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['washing_temperature'], 'value' => 'Hot (60°C)', 'sort_order' => 3, 'status' => 1];

        // Ironing Instructions values
        $values[] = ['specification_id' => $specs['ironing_instructions'], 'value' => 'Do Not Iron', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['ironing_instructions'], 'value' => 'Low Heat', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['ironing_instructions'], 'value' => 'Medium Heat', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['ironing_instructions'], 'value' => 'High Heat', 'sort_order' => 4, 'status' => 1];

        // Origin values
        $values[] = ['specification_id' => $specs['origin'], 'value' => 'India', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['origin'], 'value' => 'China', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['origin'], 'value' => 'Bangladesh', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['origin'], 'value' => 'Turkey', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['origin'], 'value' => 'USA', 'sort_order' => 5, 'status' => 1];

        // Rug Type values
        $values[] = ['specification_id' => $specs['rug_type'], 'value' => 'Persian', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_type'], 'value' => 'Modern', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_type'], 'value' => 'Traditional', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_type'], 'value' => 'Shag', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_type'], 'value' => 'Flat Weave', 'sort_order' => 5, 'status' => 1];

        // Rug Material values
        $values[] = ['specification_id' => $specs['rug_material'], 'value' => 'Wool', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_material'], 'value' => 'Cotton', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_material'], 'value' => 'Synthetic', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_material'], 'value' => 'Silk', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['rug_material'], 'value' => 'Jute', 'sort_order' => 5, 'status' => 1];

        foreach ($values as $value) {
            $value['created_at'] = now();
            $value['updated_at'] = now();
            DB::table('specification_values')->insert($value);
        }
    }

    private function seedSpecGroupSpecs()
    {
        $groups = DB::table('specification_groups')->pluck('id', 'name');
        $specs = DB::table('specifications')->pluck('id', 'code');

        $groupSpecs = [
            // Material & Fabric group
            ['spec_group_id' => $groups['Material & Fabric'], 'specification_id' => $specs['main_fabric'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Material & Fabric'], 'specification_id' => $specs['fabric_composition'], 'sort_order' => 2],
            ['spec_group_id' => $groups['Material & Fabric'], 'specification_id' => $specs['fabric_weight'], 'sort_order' => 3],
            ['spec_group_id' => $groups['Material & Fabric'], 'specification_id' => $specs['pattern'], 'sort_order' => 4],
            ['spec_group_id' => $groups['Material & Fabric'], 'specification_id' => $specs['color_family'], 'sort_order' => 5],

            // Size & Fit group
            ['spec_group_id' => $groups['Size & Fit'], 'specification_id' => $specs['gender'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Size & Fit'], 'specification_id' => $specs['age_group'], 'sort_order' => 2],

            // Care Instructions group
            ['spec_group_id' => $groups['Care Instructions'], 'specification_id' => $specs['care_instructions'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Care Instructions'], 'specification_id' => $specs['washing_temperature'], 'sort_order' => 2],
            ['spec_group_id' => $groups['Care Instructions'], 'specification_id' => $specs['ironing_instructions'], 'sort_order' => 3],

            // Additional Details group
            ['spec_group_id' => $groups['Additional Details'], 'specification_id' => $specs['origin'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Additional Details'], 'specification_id' => $specs['rug_type'], 'sort_order' => 2],
            ['spec_group_id' => $groups['Additional Details'], 'specification_id' => $specs['rug_material'], 'sort_order' => 3],
        ];

        foreach ($groupSpecs as $groupSpec) {
            $groupSpec['created_at'] = now();
            $groupSpec['updated_at'] = now();
            DB::table('spec_group_specs')->insert($groupSpec);
        }
    }

    private function seedCategorySpecGroups()
    {
        $categories = DB::table('categories')->pluck('id', 'slug');
        $groups = DB::table('specification_groups')->pluck('id', 'name');

        $categoryGroups = [];

        foreach ($categories as $slug => $categoryId) {
            foreach ($groups as $groupName => $groupId) {
                // Skip rug specifications for clothing categories
                if (in_array($slug, ['womens-wear', 'mens-wear', 'girls-wear', 'kids-wear']) && 
                    in_array($groupName, ['Additional Details'])) {
                    continue;
                }

                // Skip clothing specifications for rugs category
                if ($slug === 'rugs' && in_array($groupName, ['Size & Fit'])) {
                    continue;
                }

                $categoryGroups[] = [
                    'category_id' => $categoryId,
                    'spec_group_id' => $groupId,
                    'sort_order' => array_search($groupName, array_keys($groups->toArray())) + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('category_spec_groups')->insert($categoryGroups);
    }

    private function seedAttributes()
    {
        $attributes = [
            [
                'name' => 'Color',
                'code' => 'color',
                'type' => 'color',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Size',
                'code' => 'size',
                'type' => 'select',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clothing Size',
                'code' => 'clothing_size',
                'type' => 'select',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rug Size',
                'code' => 'rug_size',
                'type' => 'select',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kids Size',
                'code' => 'kids_size',
                'type' => 'select',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 5,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('attributes')->insert($attributes);
    }

    private function seedAttributeValues()
    {
        $attributes = DB::table('attributes')->pluck('id', 'code');
        $values = [];

        // Color attribute values
        $colors = [
            ['attribute_id' => $attributes['color'], 'value' => 'White', 'label' => 'White', 'color_code' => '#FFFFFF', 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Black', 'label' => 'Black', 'color_code' => '#000000', 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Red', 'label' => 'Red', 'color_code' => '#FF0000', 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Blue', 'label' => 'Blue', 'color_code' => '#0000FF', 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Green', 'label' => 'Green', 'color_code' => '#008000', 'sort_order' => 5, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Yellow', 'label' => 'Yellow', 'color_code' => '#FFFF00', 'sort_order' => 6, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Pink', 'label' => 'Pink', 'color_code' => '#FFC0CB', 'sort_order' => 7, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Grey', 'label' => 'Grey', 'color_code' => '#808080', 'sort_order' => 8, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Brown', 'label' => 'Brown', 'color_code' => '#A52A2A', 'sort_order' => 9, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Multi', 'label' => 'Multi Color', 'color_code' => null, 'sort_order' => 10, 'status' => 1],
        ];

        // Size attribute values (General)
        $sizes = [
            ['attribute_id' => $attributes['size'], 'value' => 'XS', 'label' => 'Extra Small', 'color_code' => null, 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'S', 'label' => 'Small', 'color_code' => null, 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'M', 'label' => 'Medium', 'color_code' => null, 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'L', 'label' => 'Large', 'color_code' => null, 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'XL', 'label' => 'Extra Large', 'color_code' => null, 'sort_order' => 5, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'XXL', 'label' => 'Double Extra Large', 'color_code' => null, 'sort_order' => 6, 'status' => 1],
        ];

        // Clothing Size attribute values (Specific)
        $clothingSizes = [
            ['attribute_id' => $attributes['clothing_size'], 'value' => '28', 'label' => 'Size 28', 'color_code' => null, 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['clothing_size'], 'value' => '30', 'label' => 'Size 30', 'color_code' => null, 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['clothing_size'], 'value' => '32', 'label' => 'Size 32', 'color_code' => null, 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['clothing_size'], 'value' => '34', 'label' => 'Size 34', 'color_code' => null, 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['clothing_size'], 'value' => '36', 'label' => 'Size 36', 'color_code' => null, 'sort_order' => 5, 'status' => 1],
            ['attribute_id' => $attributes['clothing_size'], 'value' => '38', 'label' => 'Size 38', 'color_code' => null, 'sort_order' => 6, 'status' => 1],
            ['attribute_id' => $attributes['clothing_size'], 'value' => '40', 'label' => 'Size 40', 'color_code' => null, 'sort_order' => 7, 'status' => 1],
        ];

        // Rug Size attribute values
        $rugSizes = [
            ['attribute_id' => $attributes['rug_size'], 'value' => '2x3', 'label' => '2x3 Feet', 'color_code' => null, 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['rug_size'], 'value' => '3x5', 'label' => '3x5 Feet', 'color_code' => null, 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['rug_size'], 'value' => '4x6', 'label' => '4x6 Feet', 'color_code' => null, 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['rug_size'], 'value' => '5x8', 'label' => '5x8 Feet', 'color_code' => null, 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['rug_size'], 'value' => '6x9', 'label' => '6x9 Feet', 'color_code' => null, 'sort_order' => 5, 'status' => 1],
            ['attribute_id' => $attributes['rug_size'], 'value' => '8x10', 'label' => '8x10 Feet', 'color_code' => null, 'sort_order' => 6, 'status' => 1],
        ];

        // Kids Size attribute values
        $kidsSizes = [
            ['attribute_id' => $attributes['kids_size'], 'value' => '2T', 'label' => '2 Years (Toddler)', 'color_code' => null, 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['kids_size'], 'value' => '3T', 'label' => '3 Years (Toddler)', 'color_code' => null, 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['kids_size'], 'value' => '4T', 'label' => '4 Years (Toddler)', 'color_code' => null, 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['kids_size'], 'value' => '5T', 'label' => '5 Years (Toddler)', 'color_code' => null, 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['kids_size'], 'value' => '6-7', 'label' => '6-7 Years', 'color_code' => null, 'sort_order' => 5, 'status' => 1],
            ['attribute_id' => $attributes['kids_size'], 'value' => '8-9', 'label' => '8-9 Years', 'color_code' => null, 'sort_order' => 6, 'status' => 1],
            ['attribute_id' => $attributes['kids_size'], 'value' => '10-12', 'label' => '10-12 Years', 'color_code' => null, 'sort_order' => 7, 'status' => 1],
        ];

        $allValues = array_merge($colors, $sizes, $clothingSizes, $rugSizes, $kidsSizes);

        foreach ($allValues as $value) {
            $value['created_at'] = now();
            $value['updated_at'] = now();
            DB::table('attribute_values')->insert($value);
        }
    }

    private function seedCategoryAttributes()
    {
        $categories = DB::table('categories')->pluck('id', 'slug');
        $attributes = DB::table('attributes')->pluck('id', 'code');

        $categoryAttributes = [];

        // Women's Wear - Color, Size, Clothing Size
        $categoryAttributes[] = ['category_id' => $categories['womens-wear'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['womens-wear'], 'attribute_id' => $attributes['size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];
        $categoryAttributes[] = ['category_id' => $categories['womens-wear'], 'attribute_id' => $attributes['clothing_size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 3];

        // Men's Wear - Color, Size, Clothing Size
        $categoryAttributes[] = ['category_id' => $categories['mens-wear'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['mens-wear'], 'attribute_id' => $attributes['size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];
        $categoryAttributes[] = ['category_id' => $categories['mens-wear'], 'attribute_id' => $attributes['clothing_size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 3];

        // Girl's Wear - Color, Kids Size
        $categoryAttributes[] = ['category_id' => $categories['girls-wear'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['girls-wear'], 'attribute_id' => $attributes['kids_size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];

        // Kid's Wear - Color, Kids Size
        $categoryAttributes[] = ['category_id' => $categories['kids-wear'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['kids-wear'], 'attribute_id' => $attributes['kids_size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];

        // Rugs - Color, Rug Size
        $categoryAttributes[] = ['category_id' => $categories['rugs'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['rugs'], 'attribute_id' => $attributes['rug_size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];

        foreach ($categoryAttributes as $ca) {
            $ca['created_at'] = now();
            $ca['updated_at'] = now();
            DB::table('category_attributes')->insert($ca);
        }
    }

    private function seedTaxClasses()
    {
        $taxClasses = [
            [
                'name' => 'Standard Clothing',
                'code' => 'standard_clothing',
                'description' => 'Standard tax rate for clothing and apparel',
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Home Decor',
                'code' => 'home_decor',
                'description' => 'Tax rate for home decor items including rugs',
                'is_default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tax_classes')->insert($taxClasses);

        // Add tax rates
        $clothingTaxId = DB::table('tax_classes')->where('code', 'standard_clothing')->first()->id;
        $homeDecorTaxId = DB::table('tax_classes')->where('code', 'home_decor')->first()->id;

        $taxRates = [
            [
                'tax_class_id' => $clothingTaxId,
                'name' => 'US Standard Clothing',
                'country_code' => 'US',
                'state_code' => null,
                'zip_code' => null,
                'rate' => 8.25,
                'is_active' => 1,
                'priority' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tax_class_id' => $homeDecorTaxId,
                'name' => 'US Home Decor',
                'country_code' => 'US',
                'state_code' => null,
                'zip_code' => null,
                'rate' => 10.0,
                'is_active' => 1,
                'priority' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tax_rates')->insert($taxRates);
    }

    private function seedProductsAndVariants()
    {
        $categories = DB::table('categories')->pluck('id', 'slug');
        $brands = DB::table('brands')->pluck('id', 'slug');
        $clothingTaxClass = DB::table('tax_classes')->where('code', 'standard_clothing')->first();
        $homeDecorTaxClass = DB::table('tax_classes')->where('code', 'home_decor')->first();
        $attributes = DB::table('attributes')->pluck('id', 'code');
        $attributeValues = DB::table('attribute_values')->get();

        // Get attribute value IDs
        $colorValues = [];
        $sizeValues = [];
        $clothingSizeValues = [];
        $rugSizeValues = [];
        $kidsSizeValues = [];

        foreach ($attributeValues as $value) {
            if ($value->attribute_id == $attributes['color']) {
                $colorValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['size']) {
                $sizeValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['clothing_size']) {
                $clothingSizeValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['rug_size']) {
                $rugSizeValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['kids_size']) {
                $kidsSizeValues[$value->value] = $value->id;
            }
        }

        // ==================== PRODUCT DEFINITIONS ====================

        $products = [];

        // ==================== WOMEN'S WEAR (5 products) ====================
        $products[] = [
            'name' => 'Women\'s Cotton Floral Printed Dress',
            'slug' => 'womens-cotton-floral-printed-dress',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['womens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Beautiful floral printed cotton dress perfect for summer outings.',
            'description' => $this->getClothingDescription('Women\'s Cotton Floral Printed Dress', 'dress', 'womens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.4,
            'length' => 90,
            'width' => 35,
            'height' => 2,
            'meta_title' => 'Women\'s Floral Dress - Summer Cotton Dress',
            'meta_description' => 'Beautiful cotton floral printed dress for women. Perfect for summer, vacations and casual outings.',
            'meta_keywords' => 'womens dress, floral dress, cotton dress, summer dress',
            'canonical_url' => '/clothing/womens-wear/womens-cotton-floral-printed-dress',
            'product_code' => 'WW-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Women\'s Denim Jacket',
            'slug' => 'womens-denim-jacket',
            'product_type' => 'configurable',
            'brand_id' => $brands['classic-stitch'],
            'main_category_id' => $categories['womens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Classic denim jacket with comfortable fit and modern styling.',
            'description' => $this->getClothingDescription('Women\'s Denim Jacket', 'jacket', 'womens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.8,
            'length' => 65,
            'width' => 50,
            'height' => 5,
            'meta_title' => 'Women\'s Denim Jacket - Classic Jean Jacket',
            'meta_description' => 'Classic women\'s denim jacket with comfortable fit. Perfect for layering over any outfit.',
            'meta_keywords' => 'denim jacket, womens jacket, jean jacket, casual jacket',
            'canonical_url' => '/clothing/womens-wear/womens-denim-jacket',
            'product_code' => 'WW-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Women\'s Linen Palazzo Pants',
            'slug' => 'womens-linen-palazzo-pants',
            'product_type' => 'configurable',
            'brand_id' => $brands['comfort-wear'],
            'main_category_id' => $categories['womens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Comfortable linen palazzo pants with elastic waistband.',
            'description' => $this->getClothingDescription('Women\'s Linen Palazzo Pants', 'pants', 'womens'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.5,
            'length' => 95,
            'width' => 40,
            'height' => 3,
            'meta_title' => 'Women\'s Linen Palazzo Pants - Comfort Pants',
            'meta_description' => 'Lightweight and comfortable linen palazzo pants for women. Perfect for summer and casual wear.',
            'meta_keywords' => 'palazzo pants, linen pants, womens pants, comfortable pants',
            'canonical_url' => '/clothing/womens-wear/womens-linen-palazzo-pants',
            'product_code' => 'WW-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Women\'s Silk Blouse',
            'slug' => 'womens-silk-blouse',
            'product_type' => 'configurable',
            'brand_id' => $brands['trendy-styles'],
            'main_category_id' => $categories['womens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Elegant silk blouse with delicate embroidery details.',
            'description' => $this->getClothingDescription('Women\'s Silk Blouse', 'blouse', 'womens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 0.3,
            'length' => 60,
            'width' => 45,
            'height' => 2,
            'meta_title' => 'Women\'s Silk Blouse - Elegant Top',
            'meta_description' => 'Elegant silk blouse for women with beautiful embroidery. Perfect for formal occasions and office wear.',
            'meta_keywords' => 'silk blouse, womens blouse, elegant top, formal blouse',
            'canonical_url' => '/clothing/womens-wear/womens-silk-blouse',
            'product_code' => 'WW-004',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Women\'s Wool Blend Sweater',
            'slug' => 'womens-wool-blend-sweater',
            'product_type' => 'configurable',
            'brand_id' => $brands['classic-stitch'],
            'main_category_id' => $categories['womens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Warm wool blend sweater with cable knit pattern.',
            'description' => $this->getClothingDescription('Women\'s Wool Blend Sweater', 'sweater', 'womens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.6,
            'length' => 70,
            'width' => 50,
            'height' => 4,
            'meta_title' => 'Women\'s Wool Blend Sweater - Winter Wear',
            'meta_description' => 'Warm and cozy wool blend sweater for women with cable knit pattern. Perfect for winter season.',
            'meta_keywords' => 'wool sweater, womens sweater, winter wear, cable knit',
            'canonical_url' => '/clothing/womens-wear/womens-wool-blend-sweater',
            'product_code' => 'WW-005',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== MEN'S WEAR (5 products) ====================
        $products[] = [
            'name' => 'Men\'s Casual Cotton T-Shirt',
            'slug' => 'mens-casual-cotton-t-shirt',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['mens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Comfortable cotton t-shirt for daily casual wear.',
            'description' => $this->getClothingDescription('Men\'s Casual Cotton T-Shirt', 'tshirt', 'mens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.2,
            'length' => 70,
            'width' => 50,
            'height' => 2,
            'meta_title' => 'Men\'s Cotton T-Shirt - Casual Wear',
            'meta_description' => 'Comfortable cotton t-shirt for men. Perfect for daily casual wear and sports activities.',
            'meta_keywords' => 'mens tshirt, cotton tshirt, casual wear, basic tee',
            'canonical_url' => '/clothing/mens-wear/mens-casual-cotton-t-shirt',
            'product_code' => 'MW-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Men\'s Formal Dress Shirt',
            'slug' => 'mens-formal-dress-shirt',
            'product_type' => 'configurable',
            'brand_id' => $brands['classic-stitch'],
            'main_category_id' => $categories['mens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Premium cotton formal shirt for office and formal occasions.',
            'description' => $this->getClothingDescription('Men\'s Formal Dress Shirt', 'shirt', 'mens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.3,
            'length' => 75,
            'width' => 55,
            'height' => 3,
            'meta_title' => 'Men\'s Formal Dress Shirt - Office Wear',
            'meta_description' => 'Premium cotton formal dress shirt for men. Perfect for office, meetings and formal occasions.',
            'meta_keywords' => 'mens shirt, formal shirt, dress shirt, office wear',
            'canonical_url' => '/clothing/mens-wear/mens-formal-dress-shirt',
            'product_code' => 'MW-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Men\'s Denim Jeans',
            'slug' => 'mens-denim-jeans',
            'product_type' => 'configurable',
            'brand_id' => $brands['comfort-wear'],
            'main_category_id' => $categories['mens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Classic denim jeans with comfortable straight fit.',
            'description' => $this->getClothingDescription('Men\'s Denim Jeans', 'jeans', 'mens'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.7,
            'length' => 105,
            'width' => 40,
            'height' => 5,
            'meta_title' => 'Men\'s Denim Jeans - Straight Fit',
            'meta_description' => 'Classic denim jeans for men with comfortable straight fit. Perfect for casual and daily wear.',
            'meta_keywords' => 'mens jeans, denim jeans, straight fit, casual pants',
            'canonical_url' => '/clothing/mens-wear/mens-denim-jeans',
            'product_code' => 'MW-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Men\'s Winter Jacket',
            'slug' => 'mens-winter-jacket',
            'product_type' => 'configurable',
            'brand_id' => $brands['trendy-styles'],
            'main_category_id' => $categories['mens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Warm winter jacket with waterproof exterior.',
            'description' => $this->getClothingDescription('Men\'s Winter Jacket', 'jacket', 'mens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 1.2,
            'length' => 75,
            'width' => 60,
            'height' => 8,
            'meta_title' => 'Men\'s Winter Jacket - Waterproof',
            'meta_description' => 'Warm and waterproof winter jacket for men. Perfect for cold weather and outdoor activities.',
            'meta_keywords' => 'winter jacket, mens jacket, waterproof, cold weather',
            'canonical_url' => '/clothing/mens-wear/mens-winter-jacket',
            'product_code' => 'MW-004',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Men\'s Casual Shorts',
            'slug' => 'mens-casual-shorts',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['mens-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Comfortable cotton shorts for summer and casual wear.',
            'description' => $this->getClothingDescription('Men\'s Casual Shorts', 'shorts', 'mens'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.3,
            'length' => 50,
            'width' => 45,
            'height' => 3,
            'meta_title' => 'Men\'s Casual Shorts - Summer Wear',
            'meta_description' => 'Comfortable cotton shorts for men. Perfect for summer, beach and casual outdoor activities.',
            'meta_keywords' => 'mens shorts, casual shorts, summer wear, cotton shorts',
            'canonical_url' => '/clothing/mens-wear/mens-casual-shorts',
            'product_code' => 'MW-005',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== GIRL'S WEAR (5 products) ====================
        $products[] = [
            'name' => 'Girl\'s Floral Summer Dress',
            'slug' => 'girls-floral-summer-dress',
            'product_type' => 'configurable',
            'brand_id' => $brands['trendy-styles'],
            'main_category_id' => $categories['girls-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Adorable floral printed dress for girls.',
            'description' => $this->getClothingDescription('Girl\'s Floral Summer Dress', 'dress', 'girls'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.25,
            'length' => 60,
            'width' => 30,
            'height' => 2,
            'meta_title' => 'Girl\'s Floral Dress - Kids Summer Dress',
            'meta_description' => 'Adorable floral printed summer dress for girls. Perfect for parties and special occasions.',
            'meta_keywords' => 'girls dress, kids dress, floral dress, summer dress',
            'canonical_url' => '/clothing/girls-wear/girls-floral-summer-dress',
            'product_code' => 'GW-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Girl\'s Denim Jacket',
            'slug' => 'girls-denim-jacket',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['girls-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Stylish denim jacket for girls with cute patches.',
            'description' => $this->getClothingDescription('Girl\'s Denim Jacket', 'jacket', 'girls'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.4,
            'length' => 45,
            'width' => 35,
            'height' => 3,
            'meta_title' => 'Girl\'s Denim Jacket - Kids Jacket',
            'meta_description' => 'Stylish denim jacket for girls with cute decorative patches. Perfect for casual outings.',
            'meta_keywords' => 'girls jacket, denim jacket, kids jacket, casual wear',
            'canonical_url' => '/clothing/girls-wear/girls-denim-jacket',
            'product_code' => 'GW-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Girl\'s Cotton Leggings Set',
            'slug' => 'girls-cotton-leggings-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['comfort-wear'],
            'main_category_id' => $categories['girls-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Comfortable cotton leggings with matching top.',
            'description' => $this->getClothingDescription('Girl\'s Cotton Leggings Set', 'leggings', 'girls'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.3,
            'length' => 55,
            'width' => 25,
            'height' => 3,
            'meta_title' => 'Girl\'s Leggings Set - Kids Active Wear',
            'meta_description' => 'Comfortable cotton leggings set for girls with matching top. Perfect for school and playtime.',
            'meta_keywords' => 'girls leggings, kids set, active wear, cotton leggings',
            'canonical_url' => '/clothing/girls-wear/girls-cotton-leggings-set',
            'product_code' => 'GW-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Girl\'s Party Frock',
            'slug' => 'girls-party-frock',
            'product_type' => 'configurable',
            'brand_id' => $brands['trendy-styles'],
            'main_category_id' => $categories['girls-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Beautiful party frock with lace and ribbon details.',
            'description' => $this->getClothingDescription('Girl\'s Party Frock', 'frock', 'girls'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 0.35,
            'length' => 65,
            'width' => 35,
            'height' => 4,
            'meta_title' => 'Girl\'s Party Frock - Kids Party Dress',
            'meta_description' => 'Beautiful party frock for girls with lace and ribbon decorations. Perfect for birthdays and special occasions.',
            'meta_keywords' => 'party frock, girls dress, occasion wear, kids party dress',
            'canonical_url' => '/clothing/girls-wear/girls-party-frock',
            'product_code' => 'GW-004',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Girl\'s Winter Sweater',
            'slug' => 'girls-winter-sweater',
            'product_type' => 'configurable',
            'brand_id' => $brands['classic-stitch'],
            'main_category_id' => $categories['girls-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Warm and cozy sweater for girls with cute patterns.',
            'description' => $this->getClothingDescription('Girl\'s Winter Sweater', 'sweater', 'girls'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.35,
            'length' => 50,
            'width' => 40,
            'height' => 3,
            'meta_title' => 'Girl\'s Winter Sweater - Kids Warm Wear',
            'meta_description' => 'Warm and cozy sweater for girls with cute patterns. Perfect for winter season and cold weather.',
            'meta_keywords' => 'girls sweater, winter wear, kids sweater, warm clothing',
            'canonical_url' => '/clothing/girls-wear/girls-winter-sweater',
            'product_code' => 'GW-005',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== KID'S WEAR (5 products) ====================
        $products[] = [
            'name' => 'Kid\'s Cotton Romper Set',
            'slug' => 'kids-cotton-romper-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['comfort-wear'],
            'main_category_id' => $categories['kids-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Soft cotton romper set for babies and toddlers.',
            'description' => $this->getClothingDescription('Kid\'s Cotton Romper Set', 'romper', 'kids'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.2,
            'length' => 45,
            'width' => 25,
            'height' => 2,
            'meta_title' => 'Kid\'s Romper Set - Baby Clothing',
            'meta_description' => 'Soft and comfortable cotton romper set for babies and toddlers. Perfect for daily wear.',
            'meta_keywords' => 'baby romper, kids clothing, toddler wear, cotton romper',
            'canonical_url' => '/clothing/kids-wear/kids-cotton-romper-set',
            'product_code' => 'KW-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Kid\'s Printed T-Shirt',
            'slug' => 'kids-printed-t-shirt',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['kids-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Colorful printed t-shirt for kids with cartoon characters.',
            'description' => $this->getClothingDescription('Kid\'s Printed T-Shirt', 'tshirt', 'kids'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.15,
            'length' => 40,
            'width' => 30,
            'height' => 2,
            'meta_title' => 'Kid\'s Printed T-Shirt - Kids Casual Wear',
            'meta_description' => 'Colorful printed t-shirt for kids featuring favorite cartoon characters. Perfect for casual wear.',
            'meta_keywords' => 'kids tshirt, printed tee, cartoon characters, casual wear',
            'canonical_url' => '/clothing/kids-wear/kids-printed-t-shirt',
            'product_code' => 'KW-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Kid\'s Track Pants',
            'slug' => 'kids-track-pants',
            'product_type' => 'configurable',
            'brand_id' => $brands['comfort-wear'],
            'main_category_id' => $categories['kids-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Comfortable track pants for kids with elastic waistband.',
            'description' => $this->getClothingDescription('Kid\'s Track Pants', 'pants', 'kids'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 0.25,
            'length' => 55,
            'width' => 30,
            'height' => 3,
            'meta_title' => 'Kid\'s Track Pants - Kids Active Wear',
            'meta_description' => 'Comfortable track pants for kids with elastic waistband. Perfect for sports and outdoor activities.',
            'meta_keywords' => 'track pants, kids pants, active wear, sports pants',
            'canonical_url' => '/clothing/kids-wear/kids-track-pants',
            'product_code' => 'KW-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Kid\'s Hooded Sweatshirt',
            'slug' => 'kids-hooded-sweatshirt',
            'product_type' => 'configurable',
            'brand_id' => $brands['trendy-styles'],
            'main_category_id' => $categories['kids-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Warm hooded sweatshirt for kids with front pocket.',
            'description' => $this->getClothingDescription('Kid\'s Hooded Sweatshirt', 'sweatshirt', 'kids'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 0.3,
            'length' => 45,
            'width' => 40,
            'height' => 4,
            'meta_title' => 'Kid\'s Hooded Sweatshirt - Kids Winter Wear',
            'meta_description' => 'Warm hooded sweatshirt for kids with front kangaroo pocket. Perfect for cool weather.',
            'meta_keywords' => 'kids sweatshirt, hoodie, winter wear, casual top',
            'canonical_url' => '/clothing/kids-wear/kids-hooded-sweatshirt',
            'product_code' => 'KW-004',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Kid\'s Shorts Set',
            'slug' => 'kids-shorts-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['kids-wear'],
            'tax_class_id' => $clothingTaxClass->id,
            'short_description' => 'Cotton shorts set with matching t-shirt for kids.',
            'description' => $this->getClothingDescription('Kid\'s Shorts Set', 'shorts', 'kids'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 0.25,
            'length' => 40,
            'width' => 30,
            'height' => 3,
            'meta_title' => 'Kid\'s Shorts Set - Summer Wear',
            'meta_description' => 'Cotton shorts set with matching t-shirt for kids. Perfect for summer and warm weather.',
            'meta_keywords' => 'kids shorts, summer set, cotton shorts, kids summer wear',
            'canonical_url' => '/clothing/kids-wear/kids-shorts-set',
            'product_code' => 'KW-005',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== RUGS (5 products) ====================
        $products[] = [
            'name' => 'Persian Style Wool Rug',
            'slug' => 'persian-style-wool-rug',
            'product_type' => 'configurable',
            'brand_id' => $brands['classic-stitch'],
            'main_category_id' => $categories['rugs'],
            'tax_class_id' => $homeDecorTaxClass->id,
            'short_description' => 'Beautiful Persian style wool rug with traditional patterns.',
            'description' => $this->getRugDescription('Persian Style Wool Rug', 'persian'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 5.0,
            'length' => 180,
            'width' => 120,
            'height' => 2,
            'meta_title' => 'Persian Style Wool Rug - Traditional Carpet',
            'meta_description' => 'Beautiful Persian style wool rug with traditional patterns. Perfect for living room and bedroom decor.',
            'meta_keywords' => 'persian rug, wool carpet, traditional rug, home decor',
            'canonical_url' => '/home/rugs/persian-style-wool-rug',
            'product_code' => 'RUG-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Modern Geometric Pattern Rug',
            'slug' => 'modern-geometric-pattern-rug',
            'product_type' => 'configurable',
            'brand_id' => $brands['urban-threads'],
            'main_category_id' => $categories['rugs'],
            'tax_class_id' => $homeDecorTaxClass->id,
            'short_description' => 'Contemporary rug with geometric patterns for modern interiors.',
            'description' => $this->getRugDescription('Modern Geometric Pattern Rug', 'modern'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 4.5,
            'length' => 200,
            'width' => 150,
            'height' => 1.5,
            'meta_title' => 'Modern Geometric Rug - Contemporary Decor',
            'meta_description' => 'Contemporary rug with geometric patterns for modern interior design. Perfect for living areas.',
            'meta_keywords' => 'modern rug, geometric pattern, contemporary carpet, decor rug',
            'canonical_url' => '/home/rugs/modern-geometric-pattern-rug',
            'product_code' => 'RUG-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Soft Shaggy Rug',
            'slug' => 'soft-shaggy-rug',
            'product_type' => 'configurable',
            'brand_id' => $brands['comfort-wear'],
            'main_category_id' => $categories['rugs'],
            'tax_class_id' => $homeDecorTaxClass->id,
            'short_description' => 'Ultra-soft shaggy rug for bedroom and cozy spaces.',
            'description' => $this->getRugDescription('Soft Shaggy Rug', 'shaggy'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 3.8,
            'length' => 160,
            'width' => 100,
            'height' => 5,
            'meta_title' => 'Soft Shaggy Rug - Bedroom Carpet',
            'meta_description' => 'Ultra-soft shaggy rug perfect for bedroom and cozy living spaces. Provides comfort and warmth.',
            'meta_keywords' => 'shaggy rug, soft carpet, bedroom rug, cozy rug',
            'canonical_url' => '/home/rugs/soft-shaggy-rug',
            'product_code' => 'RUG-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Traditional Indian Dhurrie Rug',
            'slug' => 'traditional-indian-dhurrie-rug',
            'product_type' => 'configurable',
            'brand_id' => $brands['classic-stitch'],
            'main_category_id' => $categories['rugs'],
            'tax_class_id' => $homeDecorTaxClass->id,
            'short_description' => 'Handwoven traditional Indian dhurrie rug with vibrant colors.',
            'description' => $this->getRugDescription('Traditional Indian Dhurrie Rug', 'dhurrie'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 3.5,
            'length' => 180,
            'width' => 120,
            'height' => 0.8,
            'meta_title' => 'Traditional Indian Dhurrie Rug - Handwoven Carpet',
            'meta_description' => 'Handwoven traditional Indian dhurrie rug with vibrant colors and patterns. Perfect for ethnic decor.',
            'meta_keywords' => 'dhurrie rug, indian carpet, handwoven rug, traditional floor covering',
            'canonical_url' => '/home/rugs/traditional-indian-dhurrie-rug',
            'product_code' => 'RUG-004',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Jute Natural Fiber Rug',
            'slug' => 'jute-natural-fiber-rug',
            'product_type' => 'configurable',
            'brand_id' => $brands['trendy-styles'],
            'main_category_id' => $categories['rugs'],
            'tax_class_id' => $homeDecorTaxClass->id,
            'short_description' => 'Eco-friendly jute rug with natural texture for sustainable decor.',
            'description' => $this->getRugDescription('Jute Natural Fiber Rug', 'jute'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 4.2,
            'length' => 170,
            'width' => 110,
            'height' => 1.2,
            'meta_title' => 'Jute Natural Fiber Rug - Eco-Friendly Carpet',
            'meta_description' => 'Eco-friendly jute rug with natural texture. Perfect for sustainable home decor and boho style interiors.',
            'meta_keywords' => 'jute rug, eco-friendly carpet, natural fiber, sustainable decor',
            'canonical_url' => '/home/rugs/jute-natural-fiber-rug',
            'product_code' => 'RUG-005',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== INSERT PRODUCTS AND CREATE VARIANTS ====================

        $productIds = [];

        foreach ($products as $index => $productData) {
            // Insert product
            $productId = DB::table('products')->insertGetId($productData);
            $productIds[] = $productId;

            // Create variants based on product category
            $this->createProductVariants(
                $productId,
                $productData,
                $categories,
                $colorValues,
                $sizeValues,
                $clothingSizeValues,
                $rugSizeValues,
                $kidsSizeValues
            );

            // Add product specifications
            $this->addProductSpecifications($productId, $productData);

            // Store product ID in array for relationships
            $products[$index]['id'] = $productId;
        }

        return $productIds;
    }

    private function createProductVariants(
        $productId,
        $productData,
        $categories,
        $colorValues,
        $sizeValues,
        $clothingSizeValues,
        $rugSizeValues,
        $kidsSizeValues
    ) {
        $categorySlug = collect($categories)
            ->flip()
            ->get($productData['main_category_id']);

        if (!$categorySlug) {
            return;
        }

        $variants = [];

        switch ($categorySlug) {
            case 'womens-wear':
                $variants = $this->createWomensWearVariants($productId, $colorValues, $sizeValues, $clothingSizeValues);
                break;

            case 'mens-wear':
                $variants = $this->createMensWearVariants($productId, $colorValues, $sizeValues, $clothingSizeValues);
                break;

            case 'girls-wear':
                $variants = $this->createGirlsWearVariants($productId, $colorValues, $kidsSizeValues);
                break;

            case 'kids-wear':
                $variants = $this->createKidsWearVariants($productId, $colorValues, $kidsSizeValues);
                break;

            case 'rugs':
                $variants = $this->createRugVariants($productId, $colorValues, $rugSizeValues);
                break;
        }

        foreach ($variants as $variantData) {
            $variantId = DB::table('product_variants')->insertGetId($variantData['variant']);

            foreach ($variantData['attributes'] as $attributeData) {
                DB::table('variant_attributes')->insert([
                    'variant_id' => $variantId,
                    'attribute_id' => $attributeData['attribute_id'],
                    'attribute_value_id' => $attributeData['attribute_value_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->addVariantImages($variantId, $productId);
        }
    }

    private function createWomensWearVariants($productId, $colorValues, $sizeValues, $clothingSizeValues)
    {
        $variants = [];
        $colors = ['Black', 'White', 'Blue', 'Red', 'Pink'];
        $sizes = ['S', 'M', 'L'];
        $clothingSizes = ['32', '34', '36'];
        $skuBase = 'WW-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                foreach ($clothingSizes as $clothingSize) {
                    if ($variantCount > 6) break 3; // Limit variants

                    $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                    $combinationHash = md5($color . $size . $clothingSize);

                    $basePrice = $this->getClothingBasePrice('womens');
                    $variants[] = [
                        'variant' => [
                            'product_id' => $productId,
                            'sku' => $sku,
                            'combination_hash' => $combinationHash,
                            'price' => $basePrice,
                            'compare_price' => round($basePrice * 1.4, 2),
                            'cost_price' => round($basePrice * 0.4, 2),
                            'stock_quantity' => rand(20, 100),
                            'reserved_quantity' => 0,
                            'stock_status' => 'in_stock',
                            'is_default' => ($variantCount === 1) ? 1 : 0,
                            'status' => 1,
                            'weight' => rand(200, 800) / 1000, // Convert to kg
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        'attributes' => [
                            ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                            ['attribute_id' => DB::table('attributes')->where('code', 'size')->first()->id, 'attribute_value_id' => $sizeValues[$size]],
                            ['attribute_id' => DB::table('attributes')->where('code', 'clothing_size')->first()->id, 'attribute_value_id' => $clothingSizeValues[$clothingSize]],
                        ]
                    ];
                    $variantCount++;
                }
            }
        }

        return $variants;
    }

    private function createMensWearVariants($productId, $colorValues, $sizeValues, $clothingSizeValues)
    {
        $variants = [];
        $colors = ['Black', 'White', 'Blue', 'Grey', 'Brown'];
        $sizes = ['M', 'L', 'XL'];
        $clothingSizes = ['34', '36', '38'];
        $skuBase = 'MW-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                foreach ($clothingSizes as $clothingSize) {
                    if ($variantCount > 6) break 3; // Limit variants

                    $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                    $combinationHash = md5($color . $size . $clothingSize);

                    $basePrice = $this->getClothingBasePrice('mens');
                    $variants[] = [
                        'variant' => [
                            'product_id' => $productId,
                            'sku' => $sku,
                            'combination_hash' => $combinationHash,
                            'price' => $basePrice,
                            'compare_price' => round($basePrice * 1.4, 2),
                            'cost_price' => round($basePrice * 0.4, 2),
                            'stock_quantity' => rand(25, 120),
                            'reserved_quantity' => 0,
                            'stock_status' => 'in_stock',
                            'is_default' => ($variantCount === 1) ? 1 : 0,
                            'status' => 1,
                            'weight' => rand(300, 1200) / 1000, // Convert to kg
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        'attributes' => [
                            ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                            ['attribute_id' => DB::table('attributes')->where('code', 'size')->first()->id, 'attribute_value_id' => $sizeValues[$size]],
                            ['attribute_id' => DB::table('attributes')->where('code', 'clothing_size')->first()->id, 'attribute_value_id' => $clothingSizeValues[$clothingSize]],
                        ]
                    ];
                    $variantCount++;
                }
            }
        }

        return $variants;
    }

    private function createGirlsWearVariants($productId, $colorValues, $kidsSizeValues)
    {
        $variants = [];
        $colors = ['Pink', 'White', 'Blue', 'Yellow', 'Multi'];
        $sizes = ['4T', '6-7', '8-9'];
        $skuBase = 'GW-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                if ($variantCount > 6) break 2; // Limit variants

                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $basePrice = $this->getClothingBasePrice('kids');
                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => $basePrice,
                        'compare_price' => round($basePrice * 1.5, 2),
                        'cost_price' => round($basePrice * 0.3, 2),
                        'stock_quantity' => rand(15, 80),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(150, 500) / 1000, // Convert to kg
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'kids_size')->first()->id, 'attribute_value_id' => $kidsSizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createKidsWearVariants($productId, $colorValues, $kidsSizeValues)
    {
        $variants = [];
        $colors = ['Blue', 'Green', 'Yellow', 'Red', 'Multi'];
        $sizes = ['2T', '3T', '4T'];
        $skuBase = 'KW-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                if ($variantCount > 6) break 2; // Limit variants

                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $basePrice = $this->getClothingBasePrice('kids');
                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => $basePrice,
                        'compare_price' => round($basePrice * 1.5, 2),
                        'cost_price' => round($basePrice * 0.3, 2),
                        'stock_quantity' => rand(20, 90),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(100, 400) / 1000, // Convert to kg
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'kids_size')->first()->id, 'attribute_value_id' => $kidsSizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createRugVariants($productId, $colorValues, $rugSizeValues)
    {
        $variants = [];
        $colors = ['Multi', 'Red', 'Blue', 'Brown', 'Grey'];
        $sizes = ['4x6', '5x8', '6x9'];
        $skuBase = 'RUG-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                if ($variantCount > 6) break 2; // Limit variants

                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $basePrice = $this->getRugBasePrice($size);
                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => $basePrice,
                        'compare_price' => round($basePrice * 1.6, 2),
                        'cost_price' => round($basePrice * 0.5, 2),
                        'stock_quantity' => rand(5, 30),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => $this->getRugWeight($size),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'rug_size')->first()->id, 'attribute_value_id' => $rugSizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function getClothingBasePrice($category)
    {
        $prices = [
            'womens' => rand(25, 80),
            'mens' => rand(20, 70),
            'kids' => rand(15, 40),
        ];

        return $prices[$category] ?? 30;
    }

    private function getRugBasePrice($size)
    {
        $sizePrices = [
            '4x6' => rand(80, 150),
            '5x8' => rand(120, 250),
            '6x9' => rand(180, 350),
        ];

        return $sizePrices[$size] ?? 100;
    }

    private function getRugWeight($size)
    {
        $sizeWeights = [
            '4x6' => 3.0,
            '5x8' => 5.0,
            '6x9' => 7.0,
        ];

        return $sizeWeights[$size] ?? 4.0;
    }

    private function addVariantImages($variantId, $productId)
    {
        $mediaIds = [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

        $selectedIndices = array_rand($mediaIds, rand(2, 4));
        if (!is_array($selectedIndices)) {
            $selectedIndices = [$selectedIndices];
        }

        $isPrimary = true;
        foreach ($selectedIndices as $index => $mediaIndex) {
            DB::table('variant_images')->insert([
                'variant_id' => $variantId,
                'media_id' => $mediaIds[$mediaIndex],
                'is_primary' => $isPrimary ? 1 : 0,
                'sort_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $isPrimary = false;
        }
    }

    private function addProductSpecifications($productId, $productData)
    {
        $specs = DB::table('specifications')->pluck('id', 'code');
        $specValues = DB::table('specification_values')->get()->groupBy('specification_id');

        $baseSpecs = [];

        // Get category from product data
        $categorySlug = DB::table('categories')
            ->where('id', $productData['main_category_id'])
            ->value('slug');

        if (in_array($categorySlug, ['womens-wear', 'mens-wear', 'girls-wear', 'kids-wear'])) {
            // Clothing specifications
            $baseSpecs = [
                [
                    'specification_id' => $specs['main_fabric'],
                    'specification_value_id' => $this->getRandomClothingFabricId($specValues[$specs['main_fabric']], $productData['name']),
                ],
                [
                    'specification_id' => $specs['fabric_composition'],
                    'specification_value_id' => null,
                    'custom_value' => $this->getFabricComposition($productData['name']),
                ],
                [
                    'specification_id' => $specs['fabric_weight'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['fabric_weight']], 'Medium Weight'),
                ],
                [
                    'specification_id' => $specs['pattern'],
                    'specification_value_id' => $this->getRandomClothingPatternId($specValues[$specs['pattern']], $productData['name']),
                ],
                [
                    'specification_id' => $specs['color_family'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['color_family']], 'Multi Color'),
                ],
                [
                    'specification_id' => $specs['gender'],
                    'specification_value_id' => $this->getGenderSpecValueId($specValues[$specs['gender']], $categorySlug),
                ],
                [
                    'specification_id' => $specs['age_group'],
                    'specification_value_id' => $this->getAgeGroupSpecValueId($specValues[$specs['age_group']], $categorySlug),
                ],
                [
                    'specification_id' => $specs['care_instructions'],
                    'specification_value_id' => null,
                    'custom_value' => 'Machine wash cold with like colors. Tumble dry low. Do not bleach. Iron on low heat if needed.',
                ],
                [
                    'specification_id' => $specs['washing_temperature'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['washing_temperature']], 'Cold (30°C)'),
                ],
                [
                    'specification_id' => $specs['ironing_instructions'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['ironing_instructions']], 'Low Heat'),
                ],
                [
                    'specification_id' => $specs['origin'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['origin']], 'India'),
                ],
            ];
        } elseif ($categorySlug === 'rugs') {
            // Rug specifications
            $baseSpecs = [
                [
                    'specification_id' => $specs['main_fabric'],
                    'specification_value_id' => $this->getRandomRugMaterialId($specValues[$specs['main_fabric']], $productData['name']),
                ],
                [
                    'specification_id' => $specs['fabric_composition'],
                    'specification_value_id' => null,
                    'custom_value' => $this->getRugMaterialComposition($productData['name']),
                ],
                [
                    'specification_id' => $specs['pattern'],
                    'specification_value_id' => $this->getRandomRugPatternId($specValues[$specs['pattern']], $productData['name']),
                ],
                [
                    'specification_id' => $specs['color_family'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['color_family']], 'Multi Color'),
                ],
                [
                    'specification_id' => $specs['care_instructions'],
                    'specification_value_id' => null,
                    'custom_value' => 'Vacuum regularly. Spot clean with mild detergent and cold water. Professional cleaning recommended for deep cleaning.',
                ],
                [
                    'specification_id' => $specs['origin'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['origin']], 'India'),
                ],
                [
                    'specification_id' => $specs['rug_type'],
                    'specification_value_id' => $this->getRandomRugTypeId($specValues[$specs['rug_type']], $productData['name']),
                ],
                [
                    'specification_id' => $specs['rug_material'],
                    'specification_value_id' => $this->getRandomRugMaterialSpecId($specValues[$specs['rug_material']], $productData['name']),
                ],
            ];
        }

        // Insert specifications
        foreach ($baseSpecs as $spec) {
            DB::table('product_specifications')->insert([
                'product_id' => $productId,
                'specification_id' => $spec['specification_id'],
                'specification_value_id' => $spec['specification_value_id'],
                'custom_value' => $spec['custom_value'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getRandomClothingFabricId($values, $productName)
    {
        if (strpos($productName, 'Cotton') !== false) return $this->getSpecValueId($values, 'Cotton');
        if (strpos($productName, 'Denim') !== false) return $this->getSpecValueId($values, 'Denim');
        if (strpos($productName, 'Linen') !== false) return $this->getSpecValueId($values, 'Linen');
        if (strpos($productName, 'Silk') !== false) return $this->getSpecValueId($values, 'Silk');
        if (strpos($productName, 'Wool') !== false) return $this->getSpecValueId($values, 'Wool');
        return $this->getRandomSpecValueId($values, null);
    }

    private function getFabricComposition($productName)
    {
        if (strpos($productName, 'Cotton') !== false) return '100% Cotton';
        if (strpos($productName, 'Denim') !== false) return '98% Cotton, 2% Elastane';
        if (strpos($productName, 'Linen') !== false) return '100% Linen';
        if (strpos($productName, 'Silk') !== false) return '100% Silk';
        if (strpos($productName, 'Wool') !== false) return '70% Wool, 30% Acrylic';
        return '100% Cotton';
    }

    private function getRandomClothingPatternId($values, $productName)
    {
        if (strpos($productName, 'Floral') !== false) return $this->getSpecValueId($values, 'Floral');
        if (strpos($productName, 'Printed') !== false) return $this->getSpecValueId($values, 'Printed');
        if (strpos($productName, 'Geometric') !== false) return $this->getSpecValueId($values, 'Geometric');
        return $this->getSpecValueId($values, 'Solid');
    }

    private function getGenderSpecValueId($values, $categorySlug)
    {
        $genderMap = [
            'womens-wear' => 'Women',
            'mens-wear' => 'Men',
            'girls-wear' => 'Girls',
            'kids-wear' => 'Boys',
        ];

        return $this->getSpecValueId($values, $genderMap[$categorySlug] ?? 'Women');
    }

    private function getAgeGroupSpecValueId($values, $categorySlug)
    {
        $ageGroupMap = [
            'womens-wear' => 'Adult',
            'mens-wear' => 'Adult',
            'girls-wear' => 'Kids (5-12 years)',
            'kids-wear' => 'Toddler (2-4 years)',
        ];

        return $this->getSpecValueId($values, $ageGroupMap[$categorySlug] ?? 'Adult');
    }

    private function getRandomRugMaterialId($values, $productName)
    {
        if (strpos($productName, 'Wool') !== false) return $this->getSpecValueId($values, 'Wool');
        if (strpos($productName, 'Jute') !== false) return $this->getSpecValueId($values, 'Wool'); // Default to Wool for simplicity
        return $this->getSpecValueId($values, 'Wool');
    }

    private function getRugMaterialComposition($productName)
    {
        if (strpos($productName, 'Wool') !== false) return '100% Wool Pile, Cotton Backing';
        if (strpos($productName, 'Jute') !== false) return '100% Natural Jute Fiber';
        return '100% Wool Pile, Cotton Backing';
    }

    private function getRandomRugPatternId($values, $productName)
    {
        if (strpos($productName, 'Geometric') !== false) return $this->getSpecValueId($values, 'Geometric');
        if (strpos($productName, 'Persian') !== false) return $this->getSpecValueId($values, 'Printed');
        return $this->getSpecValueId($values, 'Printed');
    }

    private function getRandomRugTypeId($values, $productName)
    {
        if (strpos($productName, 'Persian') !== false) return $this->getSpecValueId($values, 'Persian');
        if (strpos($productName, 'Modern') !== false) return $this->getSpecValueId($values, 'Modern');
        if (strpos($productName, 'Shaggy') !== false) return $this->getSpecValueId($values, 'Shag');
        if (strpos($productName, 'Dhurrie') !== false) return $this->getSpecValueId($values, 'Flat Weave');
        if (strpos($productName, 'Jute') !== false) return $this->getSpecValueId($values, 'Traditional');
        return $this->getSpecValueId($values, 'Traditional');
    }

    private function getRandomRugMaterialSpecId($values, $productName)
    {
        if (strpos($productName, 'Wool') !== false) return $this->getSpecValueId($values, 'Wool');
        if (strpos($productName, 'Jute') !== false) return $this->getSpecValueId($values, 'Jute');
        return $this->getSpecValueId($values, 'Wool');
    }

    private function getSpecValueId($values, $valueText)
    {
        if (!$values) return null;

        foreach ($values as $value) {
            if ($value->value === $valueText) {
                return $value->id;
            }
        }

        return null;
    }

    private function getRandomSpecValueId($values, $preferredValue = null)
    {
        if ($preferredValue && $values) {
            foreach ($values as $value) {
                if ($value->value === $preferredValue) {
                    return $value->id;
                }
            }
        }

        if ($values && count($values) > 0) {
            return $values[array_rand($values->toArray())]->id;
        }

        return null;
    }

    private function seedProductRelationships()
    {
        $products = DB::table('products')->get();
        $categories = DB::table('categories')->pluck('id', 'slug');
        $tags = DB::table('tags')->pluck('id', 'name');

        foreach ($products as $product) {
            // Add to main category (primary)
            DB::table('category_product')->insert([
                'product_id' => $product->id,
                'category_id' => $product->main_category_id,
                'is_primary' => 1,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add to 1-2 additional related categories (random)
            $allCategoryIds = array_values($categories->toArray());
            $additionalCount = rand(1, 2);
            $additionalCategories = array_rand($allCategoryIds, $additionalCount);

            if (!is_array($additionalCategories)) {
                $additionalCategories = [$additionalCategories];
            }

            foreach ($additionalCategories as $catIndex) {
                $categoryId = $allCategoryIds[$catIndex];
                if ($categoryId != $product->main_category_id) {
                    DB::table('category_product')->insert([
                        'product_id' => $product->id,
                        'category_id' => $categoryId,
                        'is_primary' => 0,
                        'sort_order' => rand(1, 10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Add tags based on product characteristics
            $productTags = ['New Arrival'];

            // Material tags
            if (strpos($product->name, 'Cotton') !== false) {
                $productTags[] = 'Cotton';
            }
            if (strpos($product->name, 'Denim') !== false) {
                $productTags[] = 'Cotton'; // Denim is cotton-based
            }
            if (strpos($product->name, 'Linen') !== false) {
                $productTags[] = 'Linen';
            }
            if (strpos($product->name, 'Silk') !== false) {
                $productTags[] = 'Silk';
            }
            if (strpos($product->name, 'Wool') !== false) {
                $productTags[] = 'Cotton'; // Default for wool
            }

            // Season tags
            if (strpos($product->name, 'Summer') !== false ||
                strpos($product->slug, 'summer') !== false ||
                strpos($product->description, 'summer') !== false) {
                $productTags[] = 'Summer Collection';
            }
            if (strpos($product->name, 'Winter') !== false ||
                strpos($product->slug, 'winter') !== false ||
                strpos($product->description, 'winter') !== false) {
                $productTags[] = 'Winter Wear';
            }

            // Style tags
            $productTags[] = 'Casual';
            if (strpos($product->name, 'Formal') !== false ||
                strpos($product->name, 'Dress') !== false ||
                strpos($product->name, 'Blouse') !== false) {
                $productTags[] = 'Formal';
            }

            // Care tags
            $productTags[] = 'Machine Washable';
            if (strpos($product->name, 'Silk') !== false) {
                $productTags[] = 'Hand Wash Only';
            }

            // Rug specific tags
            if (strpos($product->name, 'Persian') !== false ||
                strpos($product->name, 'Traditional') !== false ||
                strpos($product->name, 'Dhurrie') !== false) {
                $productTags[] = 'Traditional';
                $productTags[] = 'Handmade';
            }

            if (strpos($product->name, 'Jute') !== false ||
                strpos($product->name, 'Eco') !== false ||
                strpos($product->name, 'Natural') !== false) {
                $productTags[] = 'Eco-Friendly';
            }

            // Add bestseller tag for some products
            if (rand(0, 1)) {
                $productTags[] = 'Best Seller';
            }

            // Insert unique tags
            $uniqueTags = array_unique($productTags);
            foreach ($uniqueTags as $tagName) {
                if (isset($tags[$tagName])) {
                    DB::table('product_tags')->insert([
                        'product_id' => $product->id,
                        'tag_id' => $tags[$tagName],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Add 3-4 related products (random, but within same main category)
            $sameCategoryProducts = $products->where('main_category_id', $product->main_category_id)
                ->where('id', '!=', $product->id)
                ->pluck('id')
                ->toArray();

            $relatedCount = min(4, count($sameCategoryProducts));
            if ($relatedCount > 0) {
                $relatedIds = array_rand(array_flip($sameCategoryProducts), min($relatedCount, count($sameCategoryProducts)));

                if (!is_array($relatedIds)) {
                    $relatedIds = [$relatedIds];
                }

                foreach ($relatedIds as $relatedId) {
                    DB::table('related_products')->insert([
                        'product_id' => $product->id,
                        'related_product_id' => $relatedId,
                        'sort_order' => rand(1, 10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function getClothingDescription($productName, $type, $gender)
    {
        $descriptions = [
            'womens' => [
                'dress' => "<h2>Beautiful Women's Floral Dress</h2>
                    <p>This elegant floral printed dress is made from premium quality cotton fabric that ensures comfort and breathability throughout the day. The beautiful floral pattern adds a touch of femininity and style.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Made from 100% premium cotton</li>
                        <li>Breathable and comfortable fabric</li>
                        <li>Beautiful floral print design</li>
                        <li>Perfect for summer and casual outings</li>
                        <li>Easy care - machine washable</li>
                        <li>Available in multiple sizes and colors</li>
                    </ul>
                    <h3>Care Instructions:</h3>
                    <p>Machine wash cold with similar colors. Tumble dry low. Iron on medium heat if needed. Do not bleach.</p>
                    <h3>Perfect For:</h3>
                    <p>Summer vacations, casual outings, brunch dates, office casual wear, and weekend getaways.</p>",
                'jacket' => "<h2>Classic Women's Denim Jacket</h2>
                    <p>A timeless denim jacket that never goes out of style. Made from durable denim fabric with comfortable fit and modern styling details.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Premium quality denim fabric</li>
                        <li>Comfortable regular fit</li>
                        <li>Classic collar design</li>
                        <li>Button front closure</li>
                        <li>Two front pockets</li>
                        <li>Versatile styling options</li>
                    </ul>",
                'pants' => "<h2>Comfortable Women's Palazzo Pants</h2>
                    <p>These lightweight linen palazzo pants offer ultimate comfort with their flowing design and elastic waistband. Perfect for warm weather and casual occasions.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% pure linen fabric</li>
                        <li>Elastic waistband for comfort</li>
                        <li>Flowy palazzo cut</li>
                        <li>Lightweight and breathable</li>
                        <li>Perfect for summer wear</li>
                        <li>Easy to pair with various tops</li>
                    </ul>",
                'blouse' => "<h2>Elegant Women's Silk Blouse</h2>
                    <p>This beautiful silk blouse features delicate embroidery and premium quality silk fabric that drapes elegantly. Perfect for formal occasions and office wear.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% pure silk fabric</li>
                        <li>Delicate embroidery details</li>
                        <li>Elegant drape and fit</li>
                        <li>Button front closure</li>
                        <li>Perfect for formal occasions</li>
                        <li>Hand wash recommended</li>
                    </ul>",
                'sweater' => "<h2>Warm Women's Wool Blend Sweater</h2>
                    <p>Stay warm and stylish with this wool blend sweater featuring a classic cable knit pattern. Perfect for winter season and cold weather.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Wool blend fabric for warmth</li>
                        <li>Classic cable knit pattern</li>
                        <li>Comfortable fit</li>
                        <li>Ribbed cuffs and hem</li>
                        <li>Perfect for layering</li>
                        <li>Machine washable</li>
                    </ul>"
            ],
            'mens' => [
                'tshirt' => "<h2>Comfortable Men's Cotton T-Shirt</h2>
                    <p>Essential cotton t-shirt for daily wear. Made from soft, breathable cotton fabric that provides all-day comfort.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% premium cotton</li>
                        <li>Soft and breathable fabric</li>
                        <li>Regular fit</li>
                        <li>Ribbed neckline</li>
                        <li>Double-stitched seams for durability</li>
                        <li>Perfect for daily casual wear</li>
                    </ul>",
                'shirt' => "<h2>Premium Men's Formal Dress Shirt</h2>
                    <p>Classic formal dress shirt made from premium cotton fabric. Perfect for office, meetings, and formal occasions.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Premium cotton fabric</li>
                        <li>Classic collar design</li>
                        <li>Button front closure</li>
                        <li>Long sleeves with cuffs</li>
                        <li>Formal fit</li>
                        <li>Easy iron fabric</li>
                    </ul>",
                'jeans' => "<h2>Classic Men's Denim Jeans</h2>
                    <p>Timeless denim jeans with comfortable straight fit. Made from durable denim fabric that lasts through daily wear.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Durable denim fabric</li>
                        <li>Straight leg fit</li>
                        <li>Five-pocket design</li>
                        <li>Button and zip closure</li>
                        <li>Reinforced stress points</li>
                        <li>Classic blue wash</li>
                    </ul>",
                'jacket' => "<h2>Men's Winter Jacket</h2>
                    <p>Warm and waterproof winter jacket designed to keep you comfortable in cold weather conditions.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Waterproof exterior</li>
                        <li>Thermal lining for warmth</li>
                        <li>Multiple pockets</li>
                        <li>Adjustable hood</li>
                        <li>Zipper front closure</li>
                        <li>Durable construction</li>
                    </ul>",
                'shorts' => "<h2>Men's Casual Shorts</h2>
                    <p>Comfortable cotton shorts perfect for summer and casual wear. Features elastic waistband with drawstring for adjustable fit.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% cotton fabric</li>
                        <li>Elastic waistband with drawstring</li>
                        <li>Two side pockets</li>
                        <li>Two back pockets</li>
                        <li>Comfortable length</li>
                        <li>Perfect for summer</li>
                    </ul>"
            ],
            'girls' => [
                'dress' => "<h2>Adorable Girl's Floral Dress</h2>
                    <p>Beautiful floral dress for girls made from soft cotton fabric. Perfect for parties, school events, and special occasions.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Soft cotton fabric</li>
                        <li>Beautiful floral print</li>
                        <li>Comfortable fit</li>
                        <li>Easy wear design</li>
                        <li>Machine washable</li>
                        <li>Perfect for special occasions</li>
                    </ul>",
                'jacket' => "<h2>Stylish Girl's Denim Jacket</h2>
                    <p>Trendy denim jacket for girls with cute decorative patches. Perfect for layering over dresses and tops.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Soft denim fabric</li>
                        <li>Cute decorative patches</li>
                        <li>Button front closure</li>
                        <li>Two front pockets</li>
                        <li>Comfortable fit</li>
                        <li>Versatile styling</li>
                    </ul>",
                'leggings' => "<h2>Comfortable Girl's Leggings Set</h2>
                    <p>Soft cotton leggings with matching top. Perfect for school, playtime, and daily activities.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% cotton fabric</li>
                        <li>Elastic waistband</li>
                        <li>Matching top included</li>
                        <li>Comfortable stretch</li>
                        <li>Durable construction</li>
                        <li>Easy care - machine washable</li>
                    </ul>",
                'frock' => "<h2>Beautiful Girl's Party Frock</h2>
                    <p>Elegant party frock with lace and ribbon details. Perfect for birthdays, celebrations, and special events.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Premium quality fabric</li>
                        <li>Lace and ribbon decorations</li>
                        <li>Beautiful design</li>
                        <li>Comfortable fit</li>
                        <li>Perfect for parties</li>
                        <li>Special occasion wear</li>
                    </ul>",
                'sweater' => "<h2>Warm Girl's Winter Sweater</h2>
                    <p>Cozy sweater for girls with cute patterns. Keeps them warm and comfortable during cold weather.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Warm fabric blend</li>
                        <li>Cute pattern design</li>
                        <li>Comfortable fit</li>
                        <li>Ribbed cuffs and hem</li>
                        <li>Machine washable</li>
                        <li>Perfect for winter</li>
                    </ul>"
            ],
            'kids' => [
                'romper' => "<h2>Soft Kid's Cotton Romper Set</h2>
                    <p>Comfortable cotton romper set for babies and toddlers. Made from soft, breathable fabric perfect for sensitive skin.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% soft cotton</li>
                        <li>Gentle on baby's skin</li>
                        <li>Snap button closures</li>
                        <li>Easy diaper changes</li>
                        <li>Machine washable</li>
                        <li>Perfect for daily wear</li>
                    </ul>",
                'tshirt' => "<h2>Colorful Kid's Printed T-Shirt</h2>
                    <p>Fun printed t-shirt for kids featuring favorite cartoon characters. Made from comfortable cotton fabric.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Soft cotton fabric</li>
                        <li>Colorful cartoon prints</li>
                        <li>Comfortable fit</li>
                        <li>Kid-friendly design</li>
                        <li>Durable construction</li>
                        <li>Easy care</li>
                    </ul>",
                'pants' => "<h2>Comfortable Kid's Track Pants</h2>
                    <p>Active wear track pants for kids with elastic waistband. Perfect for sports, playtime, and outdoor activities.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Comfortable fabric</li>
                        <li>Elastic waistband</li>
                        <li>Two side pockets</li>
                        <li>Ribbed cuffs</li>
                        <li>Perfect for active kids</li>
                        <li>Machine washable</li>
                    </ul>",
                'sweatshirt' => "<h2>Warm Kid's Hooded Sweatshirt</h2>
                    <p>Cozy hooded sweatshirt for kids with front kangaroo pocket. Perfect for cool weather and casual wear.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Soft fleece fabric</li>
                        <li>Adjustable hood</li>
                        <li>Front kangaroo pocket</li>
                        <li>Ribbed cuffs and hem</li>
                        <li>Comfortable fit</li>
                        <li>Machine washable</li>
                    </ul>",
                'shorts' => "<h2>Kid's Cotton Shorts Set</h2>
                    <p>Complete summer set with cotton shorts and matching t-shirt. Perfect for warm weather and outdoor play.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>100% cotton fabric</li>
                        <li>Matching set</li>
                        <li>Elastic waistband</li>
                        <li>Comfortable fit</li>
                        <li>Perfect for summer</li>
                        <li>Easy care</li>
                    </ul>"
            ]
        ];

        return $descriptions[$gender][$type] ?? "<h2>{$productName}</h2><p>High-quality {$gender} clothing made with premium materials. Perfect for daily wear and special occasions.</p>";
    }

    private function getRugDescription($productName, $type)
    {
        $descriptions = [
            'persian' => "<h2>Beautiful Persian Style Wool Rug</h2>
                <p>This exquisite Persian style rug features traditional patterns and motifs handcrafted with premium wool. The rich colors and intricate designs make it a perfect centerpiece for any room.</p>
                <h3>Features:</h3>
                <ul>
                    <li>Made from 100% premium wool pile</li>
                    <li>Traditional Persian patterns</li>
                    <li>Rich, fade-resistant colors</li>
                    <li>Cotton backing for durability</li>
                    <li>Handcrafted quality</li>
                    <li>Perfect for living room or bedroom</li>
                </ul>
                <h3>Care Instructions:</h3>
                <p>Vacuum regularly without beater bar. Rotate periodically for even wear. Professional cleaning recommended. Spot clean with mild detergent and cold water.</p>
                <h3>Perfect For:</h3>
                <p>Living rooms, bedrooms, dining areas, and traditional style interiors. Adds warmth and elegance to any space.</p>",
            'modern' => "<h2>Contemporary Geometric Pattern Rug</h2>
                <p>Modern geometric rug featuring clean lines and contemporary patterns. Perfect for adding a touch of modern style to any interior space.</p>
                <h3>Features:</h3>
                <ul>
                    <li>Modern geometric design</li>
                    <li>Durable synthetic blend</li>
                    <li>Low pile height</li>
                    <li>Non-slip backing</li>
                    <li>Easy to clean</li>
                    <li>Perfect for contemporary interiors</li>
                </ul>",
            'shaggy' => "<h2>Ultra-Soft Shaggy Rug</h2>
                <p>Plush shaggy rug with deep pile for ultimate comfort underfoot. Perfect for bedrooms and cozy living spaces.</p>
                <h3>Features:</h3>
                <ul>
                    <li>Extra soft shag pile</li>
                    <li>Deep pile height</li>
                    <li>Luxurious feel</li>
                    <li>Non-shedding material</li>
                    <li>Perfect for bare feet</li>
                    <li>Adds texture to any room</li>
                </ul>",
            'dhurrie' => "<h2>Traditional Indian Dhurrie Rug</h2>
                <p>Handwoven traditional Indian dhurrie rug featuring vibrant colors and traditional patterns. Lightweight and reversible.</p>
                <h3>Features:</h3>
                <ul>
                    <li>Handwoven cotton construction</li>
                    <li>Vibrant traditional colors</li>
                    <li>Reversible design</li>
                    <li>Lightweight and easy to move</li>
                    <li>Flat weave pattern</li>
                    <li>Perfect for ethnic decor</li>
                </ul>",
            'jute' => "<h2>Eco-Friendly Jute Natural Fiber Rug</h2>
                <p>Sustainable jute rug with natural texture and earth-friendly materials. Perfect for eco-conscious homes and boho style interiors.</p>
                <h3>Features:</h3>
                <ul>
                    <li>100% natural jute fiber</li>
                    <li>Eco-friendly and sustainable</li>
                    <li>Natural texture and color</li>
                    <li>Biodegradable material</li>
                    <li>Adds natural warmth</li>
                    <li>Perfect for boho and rustic decor</li>
                </ul>"
        ];

        return $descriptions[$type] ?? "<h2>{$productName}</h2><p>High-quality rug made with premium materials. Perfect for home decor and adding comfort to any room.</p>";
    }
}