<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JewelryStoreSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear existing data (carefully)
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
        $this->call(SettingsSeeder::class);

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function truncateTables()
    {
        // Truncate in reverse order of dependencies
        $tables = [
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
        // Media IDs 1-20 exist as you mentioned
        // They will be referenced in variant_images
    }

    private function seedBrands()
    {
        $brands = [
            [
                'name' => 'Glamour Jewels',
                'slug' => 'glamour-jewels',
                'description' => 'Premium imitation jewelry brand offering exquisite designs',
                'logo_id' => 1,
                'status' => 1,
                'sort_order' => 1,
                'meta_title' => 'Glamour Jewels - Premium Imitation Jewelry',
                'meta_description' => 'Shop premium imitation jewelry from Glamour Jewels',
                'meta_keywords' => 'imitation jewelry, fashion jewelry, premium accessories',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sparkle & Shine',
                'slug' => 'sparkle-shine',
                'description' => 'Trendy and affordable imitation jewelry collections',
                'logo_id' => 2,
                'status' => 1,
                'sort_order' => 2,
                'meta_title' => 'Sparkle & Shine - Trendy Fashion Jewelry',
                'meta_description' => 'Affordable trendy imitation jewelry collections',
                'meta_keywords' => 'fashion jewelry, trendy accessories, affordable jewelry',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elegance Craft',
                'slug' => 'elegance-craft',
                'description' => 'Handcrafted imitation jewelry with elegant designs',
                'logo_id' => 3,
                'status' => 1,
                'sort_order' => 3,
                'meta_title' => 'Elegance Craft - Handcrafted Jewelry',
                'meta_description' => 'Beautiful handcrafted imitation jewelry pieces',
                'meta_keywords' => 'handcrafted jewelry, elegant designs, artisanal',
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
                'name' => 'Earrings',
                'slug' => 'earrings',
                'description' => 'Beautiful imitation earrings in various styles - studs, hoops, danglers',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 1,
                'image_id' => 4,
                'meta_title' => 'Imitation Earrings - Fashion Earrings Collection',
                'meta_description' => 'Shop trendy imitation earrings including studs, hoops, and danglers',
                'meta_keywords' => 'imitation earrings, fashion earrings, stud earrings, hoop earrings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Necklaces',
                'slug' => 'necklaces',
                'description' => 'Elegant imitation necklaces and chains for every occasion',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 2,
                'image_id' => 5,
                'meta_title' => 'Imitation Necklaces - Chain Necklaces Collection',
                'meta_description' => 'Beautiful imitation necklaces including pendants and statement pieces',
                'meta_keywords' => 'imitation necklaces, chain necklaces, pendant necklaces',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Rings',
                'slug' => 'rings',
                'description' => 'Stylish imitation rings for all fingers and occasions',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 3,
                'image_id' => 6,
                'meta_title' => 'Imitation Rings - Fashion Rings Collection',
                'meta_description' => 'Trendy imitation rings including cocktail rings and stackable rings',
                'meta_keywords' => 'imitation rings, fashion rings, cocktail rings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Bracelets',
                'slug' => 'bracelets',
                'description' => 'Beautiful imitation bracelets and bangles collection',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 4,
                'image_id' => 7,
                'meta_title' => 'Imitation Bracelets - Fashion Bracelets Collection',
                'meta_description' => 'Stylish imitation bracelets including charm bracelets and cuffs',
                'meta_keywords' => 'imitation bracelets, fashion bracelets, charm bracelets',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Bangles',
                'slug' => 'bangles',
                'description' => 'Traditional and modern imitation bangles collection',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 5,
                'image_id' => 8,
                'meta_title' => 'Imitation Bangles - Fashion Bangles Collection',
                'meta_description' => 'Beautiful imitation bangles including gold and silver finish',
                'meta_keywords' => 'imitation bangles, fashion bangles, traditional bangles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Pendant',
                'slug' => 'pendant',
                'description' => 'Elegant imitation pendants for necklaces and chains',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 6,
                'image_id' => 9,
                'meta_title' => 'Imitation Pendants - Jewelry Pendants Collection',
                'meta_description' => 'Beautiful imitation pendants including gemstone and crystal designs',
                'meta_keywords' => 'imitation pendants, jewelry pendants, pendant sets',
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
            // Self reference only (since we don't have subcategories)
            $hierarchies[] = [
                'ancestor_id' => $category->id,
                'descendant_id' => $category->id,
                'depth' => 0,
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ];
        }

        DB::table('category_hierarchies')->insert($hierarchies);
    }

    private function seedTags()
    {
        $tags = [
            ['name' => 'Fashion Jewelry', 'slug' => 'fashion-jewelry', 'status' => 1],
            ['name' => 'Imitation', 'slug' => 'imitation', 'status' => 1],
            ['name' => 'Gold Plated', 'slug' => 'gold-plated', 'status' => 1],
            ['name' => 'Silver Plated', 'slug' => 'silver-plated', 'status' => 1],
            ['name' => 'Crystal', 'slug' => 'crystal', 'status' => 1],
            ['name' => 'Pearl', 'slug' => 'pearl', 'status' => 1],
            ['name' => 'Gemstone', 'slug' => 'gemstone', 'status' => 1],
            ['name' => 'Party Wear', 'slug' => 'party-wear', 'status' => 1],
            ['name' => 'Daily Wear', 'slug' => 'daily-wear', 'status' => 1],
            ['name' => 'Bridal', 'slug' => 'bridal', 'status' => 1],
            ['name' => 'Trendy', 'slug' => 'trendy', 'status' => 1],
            ['name' => 'Minimalist', 'slug' => 'minimalist', 'status' => 1],
            ['name' => 'Statement', 'slug' => 'statement', 'status' => 1],
            ['name' => 'Hypoallergenic', 'slug' => 'hypoallergenic', 'status' => 1],
            ['name' => 'Water Resistant', 'slug' => 'water-resistant', 'status' => 1],
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
            ['name' => 'Material & Quality', 'sort_order' => 1, 'status' => 1],
            ['name' => 'Usage & Care', 'sort_order' => 2, 'status' => 1],
            ['name' => 'Dimensions & Fit', 'sort_order' => 3, 'status' => 1],
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
                'name' => 'Base Metal',
                'code' => 'base_metal',
                'input_type' => 'select',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plating Type',
                'code' => 'plating_type',
                'input_type' => 'select',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plating Thickness',
                'code' => 'plating_thickness',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 0,
                'sort_order' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stone Type',
                'code' => 'stone_type',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stone Color',
                'code' => 'stone_color',
                'input_type' => 'select',
                'is_required' => 0,
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
                'name' => 'Occasion',
                'code' => 'occasion',
                'input_type' => 'multiselect',
                'is_required' => 0,
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
                'name' => 'Hypoallergenic',
                'code' => 'hypoallergenic',
                'input_type' => 'checkbox',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 9,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Water Resistance',
                'code' => 'water_resistance',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 10,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Closure Type',
                'code' => 'closure_type',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 11,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Weight Range',
                'code' => 'weight_range',
                'input_type' => 'select',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 12,
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

        // Base Metal values
        $values[] = ['specification_id' => $specs['base_metal'], 'value' => 'Brass', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['base_metal'], 'value' => 'Copper', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['base_metal'], 'value' => 'Zinc Alloy', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['base_metal'], 'value' => 'Stainless Steel', 'sort_order' => 4, 'status' => 1];

        // Plating Type values
        $values[] = ['specification_id' => $specs['plating_type'], 'value' => 'Gold Plated', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['plating_type'], 'value' => 'Rose Gold Plated', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['plating_type'], 'value' => 'Silver Plated', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['plating_type'], 'value' => 'Rhodium Plated', 'sort_order' => 4, 'status' => 1];

        // Plating Thickness values
        $values[] = ['specification_id' => $specs['plating_thickness'], 'value' => 'Light (0.5-1 micron)', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['plating_thickness'], 'value' => 'Medium (1-2 microns)', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['plating_thickness'], 'value' => 'Heavy (2-3 microns)', 'sort_order' => 3, 'status' => 1];

        // Stone Type values
        $values[] = ['specification_id' => $specs['stone_type'], 'value' => 'Cubic Zirconia', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_type'], 'value' => 'Crystal', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_type'], 'value' => 'Synthetic Pearl', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_type'], 'value' => 'Glass Stone', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_type'], 'value' => 'No Stone', 'sort_order' => 5, 'status' => 1];

        // Stone Color values
        $values[] = ['specification_id' => $specs['stone_color'], 'value' => 'Clear', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_color'], 'value' => 'White', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_color'], 'value' => 'Blue', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_color'], 'value' => 'Red', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_color'], 'value' => 'Green', 'sort_order' => 5, 'status' => 1];
        $values[] = ['specification_id' => $specs['stone_color'], 'value' => 'Multicolor', 'sort_order' => 6, 'status' => 1];

        // Gender values
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Women', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['gender'], 'value' => 'Unisex', 'sort_order' => 2, 'status' => 1];

        // Occasion values
        $values[] = ['specification_id' => $specs['occasion'], 'value' => 'Party', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['occasion'], 'value' => 'Wedding', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['occasion'], 'value' => 'Casual', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['occasion'], 'value' => 'Office', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['occasion'], 'value' => 'Formal', 'sort_order' => 5, 'status' => 1];

        // Water Resistance values
        $values[] = ['specification_id' => $specs['water_resistance'], 'value' => 'Not Water Resistant', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['water_resistance'], 'value' => 'Splash Proof', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['water_resistance'], 'value' => 'Water Resistant', 'sort_order' => 3, 'status' => 1];

        // Closure Type values
        $values[] = ['specification_id' => $specs['closure_type'], 'value' => 'Lobster Clasp', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['closure_type'], 'value' => 'Spring Ring', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['closure_type'], 'value' => 'Toggle', 'sort_order' => 3, 'status' => 1];
        $values[] = ['specification_id' => $specs['closure_type'], 'value' => 'Magnetic', 'sort_order' => 4, 'status' => 1];
        $values[] = ['specification_id' => $specs['closure_type'], 'value' => 'Push Back', 'sort_order' => 5, 'status' => 1];

        // Weight Range values
        $values[] = ['specification_id' => $specs['weight_range'], 'value' => 'Light (0-10g)', 'sort_order' => 1, 'status' => 1];
        $values[] = ['specification_id' => $specs['weight_range'], 'value' => 'Medium (10-30g)', 'sort_order' => 2, 'status' => 1];
        $values[] = ['specification_id' => $specs['weight_range'], 'value' => 'Heavy (30g+)', 'sort_order' => 3, 'status' => 1];

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
            // Material & Quality group
            ['spec_group_id' => $groups['Material & Quality'], 'specification_id' => $specs['base_metal'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Material & Quality'], 'specification_id' => $specs['plating_type'], 'sort_order' => 2],
            ['spec_group_id' => $groups['Material & Quality'], 'specification_id' => $specs['plating_thickness'], 'sort_order' => 3],
            ['spec_group_id' => $groups['Material & Quality'], 'specification_id' => $specs['stone_type'], 'sort_order' => 4],
            ['spec_group_id' => $groups['Material & Quality'], 'specification_id' => $specs['stone_color'], 'sort_order' => 5],

            // Usage & Care group
            ['spec_group_id' => $groups['Usage & Care'], 'specification_id' => $specs['gender'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Usage & Care'], 'specification_id' => $specs['occasion'], 'sort_order' => 2],
            ['spec_group_id' => $groups['Usage & Care'], 'specification_id' => $specs['care_instructions'], 'sort_order' => 3],
            ['spec_group_id' => $groups['Usage & Care'], 'specification_id' => $specs['hypoallergenic'], 'sort_order' => 4],
            ['spec_group_id' => $groups['Usage & Care'], 'specification_id' => $specs['water_resistance'], 'sort_order' => 5],

            // Dimensions & Fit group
            ['spec_group_id' => $groups['Dimensions & Fit'], 'specification_id' => $specs['closure_type'], 'sort_order' => 1],
            ['spec_group_id' => $groups['Dimensions & Fit'], 'specification_id' => $specs['weight_range'], 'sort_order' => 2],
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
                'name' => 'Chain Length',
                'code' => 'chain_length',
                'type' => 'select',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ring Size',
                'code' => 'ring_size',
                'type' => 'select',
                'is_variant' => 1,
                'is_filterable' => 1,
                'sort_order' => 4,
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
            ['attribute_id' => $attributes['color'], 'value' => 'Gold', 'label' => 'Gold', 'color_code' => '#FFD700', 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Rose Gold', 'label' => 'Rose Gold', 'color_code' => '#B76E79', 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Silver', 'label' => 'Silver', 'color_code' => '#C0C0C0', 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Black', 'label' => 'Black', 'color_code' => '#000000', 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['color'], 'value' => 'Multi', 'label' => 'Multi Color', 'color_code' => null, 'sort_order' => 5, 'status' => 1],
        ];

        // Size attribute values
        $sizes = [
            ['attribute_id' => $attributes['size'], 'value' => 'Small', 'label' => 'Small', 'color_code' => null, 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'Medium', 'label' => 'Medium', 'color_code' => null, 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['size'], 'value' => 'Large', 'label' => 'Large', 'color_code' => null, 'sort_order' => 3, 'status' => 1],
        ];

        // Chain Length attribute values
        $chainLengths = [
            ['attribute_id' => $attributes['chain_length'], 'value' => '16', 'label' => '16 inches', 'color_code' => null, 'sort_order' => 1, 'status' => 1],
            ['attribute_id' => $attributes['chain_length'], 'value' => '18', 'label' => '18 inches', 'color_code' => null, 'sort_order' => 2, 'status' => 1],
            ['attribute_id' => $attributes['chain_length'], 'value' => '20', 'label' => '20 inches', 'color_code' => null, 'sort_order' => 3, 'status' => 1],
            ['attribute_id' => $attributes['chain_length'], 'value' => '22', 'label' => '22 inches', 'color_code' => null, 'sort_order' => 4, 'status' => 1],
            ['attribute_id' => $attributes['chain_length'], 'value' => '24', 'label' => '24 inches', 'color_code' => null, 'sort_order' => 5, 'status' => 1],
        ];

        // Ring Size attribute values
        $ringSizes = [];
        $usSizes = [5, 6, 7, 8, 9];
        $sortOrder = 1;
        foreach ($usSizes as $size) {
            $ringSizes[] = [
                'attribute_id' => $attributes['ring_size'],
                'value' => $size,
                'label' => "US {$size}",
                'color_code' => null,
                'sort_order' => $sortOrder++,
                'status' => 1,
            ];
        }

        $allValues = array_merge($colors, $sizes, $chainLengths, $ringSizes);

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

        // Earrings - Color and Size
        $categoryAttributes[] = ['category_id' => $categories['earrings'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['earrings'], 'attribute_id' => $attributes['size'], 'is_required' => 0, 'is_filterable' => 1, 'sort_order' => 2];

        // Necklaces - Color and Chain Length
        $categoryAttributes[] = ['category_id' => $categories['necklaces'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['necklaces'], 'attribute_id' => $attributes['chain_length'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];

        // Rings - Color and Ring Size
        $categoryAttributes[] = ['category_id' => $categories['rings'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['rings'], 'attribute_id' => $attributes['ring_size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];

        // Bracelets - Color and Size
        $categoryAttributes[] = ['category_id' => $categories['bracelets'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['bracelets'], 'attribute_id' => $attributes['size'], 'is_required' => 0, 'is_filterable' => 1, 'sort_order' => 2];

        // Bangles - Color and Size
        $categoryAttributes[] = ['category_id' => $categories['bangles'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];
        $categoryAttributes[] = ['category_id' => $categories['bangles'], 'attribute_id' => $attributes['size'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 2];

        // Pendant - Color only
        $categoryAttributes[] = ['category_id' => $categories['pendant'], 'attribute_id' => $attributes['color'], 'is_required' => 1, 'is_filterable' => 1, 'sort_order' => 1];

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
                'name' => 'Standard Jewelry',
                'code' => 'standard_jewelry',
                'description' => 'Standard tax rate for imitation jewelry',
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tax_classes')->insert($taxClasses);

        // Add tax rates
        $taxClassId = DB::table('tax_classes')->where('code', 'standard_jewelry')->first()->id;

        $taxRates = [
            [
                'tax_class_id' => $taxClassId,
                'name' => 'US Standard',
                'country_code' => 'US',
                'state_code' => null,
                'zip_code' => null,
                'rate' => 8.25,
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
        $taxClass = DB::table('tax_classes')->where('code', 'standard_jewelry')->first();
        $attributes = DB::table('attributes')->pluck('id', 'code');
        $attributeValues = DB::table('attribute_values')->get();

        // Get attribute value IDs
        $colorValues = [];
        $sizeValues = [];
        $chainLengthValues = [];
        $ringSizeValues = [];

        foreach ($attributeValues as $value) {
            if ($value->attribute_id == $attributes['color']) {
                $colorValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['size']) {
                $sizeValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['chain_length']) {
                $chainLengthValues[$value->value] = $value->id;
            } elseif ($value->attribute_id == $attributes['ring_size']) {
                $ringSizeValues[$value->value] = $value->id;
            }
        }

        // ==================== PRODUCT DEFINITIONS ====================

        $products = [];

        // ==================== EARRINGS (3 products) ====================
        $products[] = [
            'name' => 'Gold Plated Crystal Stud Earrings',
            'slug' => 'gold-plated-crystal-stud-earrings',
            'product_type' => 'configurable',
            'brand_id' => $brands['glamour-jewels'],
            'main_category_id' => $categories['earrings'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Elegant gold plated stud earrings with clear crystals. Perfect for daily wear.',
            'description' => $this->getHTMLDescription('Gold Plated Crystal Stud Earrings', 'stud', 'earrings'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 8.5,
            'length' => 0.8,
            'width' => 0.8,
            'height' => 1.2,
            'meta_title' => 'Gold Crystal Stud Earrings - Fashion Jewelry',
            'meta_description' => 'Beautiful gold plated stud earrings with clear crystals. Perfect for daily wear and special occasions.',
            'meta_keywords' => 'stud earrings, gold earrings, crystal earrings, daily wear earrings',
            'canonical_url' => '/jewelry/earrings/gold-plated-crystal-stud-earrings',
            'product_code' => 'EARR-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Silver Hoop Earrings with Pearl Detail',
            'slug' => 'silver-hoop-earrings-pearl-detail',
            'product_type' => 'configurable',
            'brand_id' => $brands['sparkle-shine'],
            'main_category_id' => $categories['earrings'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Classic silver hoop earrings with elegant pearl accents.',
            'description' => $this->getHTMLDescription('Silver Hoop Earrings with Pearl Detail', 'hoop', 'earrings'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 12.3,
            'length' => 2.5,
            'width' => 2.5,
            'height' => 0.5,
            'meta_title' => 'Silver Hoop Earrings with Pearl - Fashion Earrings',
            'meta_description' => 'Classic silver hoop earrings with beautiful pearl accents. Perfect for any occasion.',
            'meta_keywords' => 'hoop earrings, silver earrings, pearl earrings, classic earrings',
            'canonical_url' => '/jewelry/earrings/silver-hoop-earrings-pearl-detail',
            'product_code' => 'EARR-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Rose Gold Dangler Earrings with Crystals',
            'slug' => 'rose-gold-dangler-earrings-crystals',
            'product_type' => 'configurable',
            'brand_id' => $brands['elegance-craft'],
            'main_category_id' => $categories['earrings'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Elegant rose gold dangler earrings with sparkling crystals.',
            'description' => $this->getHTMLDescription('Rose Gold Dangler Earrings with Crystals', 'dangler', 'earrings'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 15.7,
            'length' => 4.2,
            'width' => 1.5,
            'height' => 0.8,
            'meta_title' => 'Rose Gold Dangler Earrings - Party Wear Jewelry',
            'meta_description' => 'Beautiful rose gold dangler earrings with sparkling crystals. Perfect for parties and special events.',
            'meta_keywords' => 'dangler earrings, rose gold earrings, crystal earrings, party wear earrings',
            'canonical_url' => '/jewelry/earrings/rose-gold-dangler-earrings-crystals',
            'product_code' => 'EARR-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== NECKLACES (3 products) ====================
        $products[] = [
            'name' => 'Gold Plated Choker Necklace with Pendant',
            'slug' => 'gold-plated-choker-necklace-pendant',
            'product_type' => 'configurable',
            'brand_id' => $brands['glamour-jewels'],
            'main_category_id' => $categories['necklaces'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Elegant gold plated choker necklace with a beautiful pendant.',
            'description' => $this->getHTMLDescription('Gold Plated Choker Necklace with Pendant', 'choker', 'necklaces'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 25.3,
            'length' => 16.0,
            'width' => 3.2,
            'height' => 0.5,
            'meta_title' => 'Gold Choker Necklace with Pendant - Fashion Necklace',
            'meta_description' => 'Elegant gold plated choker necklace with beautiful pendant design. Perfect for formal occasions.',
            'meta_keywords' => 'choker necklace, gold necklace, pendant necklace, formal jewelry',
            'canonical_url' => '/jewelry/necklaces/gold-plated-choker-necklace-pendant',
            'product_code' => 'NECK-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Silver Chain Necklace with Cubic Zirconia',
            'slug' => 'silver-chain-necklace-cubic-zirconia',
            'product_type' => 'configurable',
            'brand_id' => $brands['sparkle-shine'],
            'main_category_id' => $categories['necklaces'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Classic silver chain necklace with sparkling cubic zirconia stones.',
            'description' => $this->getHTMLDescription('Silver Chain Necklace with Cubic Zirconia', 'chain', 'necklaces'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 18.7,
            'length' => 18.0,
            'width' => 0.8,
            'height' => 0.3,
            'meta_title' => 'Silver Chain Necklace with CZ Stones - Daily Wear',
            'meta_description' => 'Classic silver chain necklace with sparkling cubic zirconia stones. Perfect for daily wear.',
            'meta_keywords' => 'chain necklace, silver necklace, cubic zirconia, daily wear necklace',
            'canonical_url' => '/jewelry/necklaces/silver-chain-necklace-cubic-zirconia',
            'product_code' => 'NECK-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Rose Gold Layered Necklace Set',
            'slug' => 'rose-gold-layered-necklace-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['elegance-craft'],
            'main_category_id' => $categories['necklaces'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Trendy rose gold layered necklace set with multiple chains.',
            'description' => $this->getHTMLDescription('Rose Gold Layered Necklace Set', 'layered', 'necklaces'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 32.5,
            'length' => 20.0,
            'width' => 5.5,
            'height' => 0.4,
            'meta_title' => 'Rose Gold Layered Necklace Set - Trendy Jewelry',
            'meta_description' => 'Trendy rose gold layered necklace set with multiple chain styles. Perfect for fashion-forward looks.',
            'meta_keywords' => 'layered necklace, rose gold necklace, necklace set, trendy jewelry',
            'canonical_url' => '/jewelry/necklaces/rose-gold-layered-necklace-set',
            'product_code' => 'NECK-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== RINGS (3 products) ====================
        $products[] = [
            'name' => 'Gold Plated Statement Cocktail Ring',
            'slug' => 'gold-plated-statement-cocktail-ring',
            'product_type' => 'configurable',
            'brand_id' => $brands['glamour-jewels'],
            'main_category_id' => $categories['rings'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Bold gold plated statement ring with crystal accents.',
            'description' => $this->getHTMLDescription('Gold Plated Statement Cocktail Ring', 'cocktail', 'rings'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 12.8,
            'length' => 2.2,
            'width' => 2.2,
            'height' => 1.5,
            'meta_title' => 'Gold Statement Cocktail Ring - Party Wear',
            'meta_description' => 'Bold gold plated statement cocktail ring with beautiful crystal accents. Perfect for parties.',
            'meta_keywords' => 'cocktail ring, statement ring, gold ring, party ring',
            'canonical_url' => '/jewelry/rings/gold-plated-statement-cocktail-ring',
            'product_code' => 'RING-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Silver Stackable Band Rings Set',
            'slug' => 'silver-stackable-band-rings-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['sparkle-shine'],
            'main_category_id' => $categories['rings'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Set of 3 silver stackable band rings for layered look.',
            'description' => $this->getHTMLDescription('Silver Stackable Band Rings Set', 'stackable', 'rings'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 0,
            'is_bestseller' => 1,
            'weight' => 8.5,
            'length' => 2.0,
            'width' => 0.3,
            'height' => 0.3,
            'meta_title' => 'Silver Stackable Band Rings Set - Minimalist Jewelry',
            'meta_description' => 'Set of 3 silver stackable band rings perfect for creating layered looks. Minimalist design.',
            'meta_keywords' => 'stackable rings, band rings, silver rings, minimalist rings',
            'canonical_url' => '/jewelry/rings/silver-stackable-band-rings-set',
            'product_code' => 'RING-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Rose Gold Solitaire Ring with CZ Stone',
            'slug' => 'rose-gold-solitaire-ring-cz-stone',
            'product_type' => 'configurable',
            'brand_id' => $brands['elegance-craft'],
            'main_category_id' => $categories['rings'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Elegant rose gold solitaire ring with cubic zirconia stone.',
            'description' => $this->getHTMLDescription('Rose Gold Solitaire Ring with CZ Stone', 'solitaire', 'rings'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 6.3,
            'length' => 1.8,
            'width' => 1.8,
            'height' => 0.8,
            'meta_title' => 'Rose Gold Solitaire Ring - Elegant Jewelry',
            'meta_description' => 'Elegant rose gold solitaire ring with beautiful cubic zirconia stone. Perfect for special occasions.',
            'meta_keywords' => 'solitaire ring, rose gold ring, CZ ring, elegant ring',
            'canonical_url' => '/jewelry/rings/rose-gold-solitaire-ring-cz-stone',
            'product_code' => 'RING-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== BRACELETS (3 products) ====================
        $products[] = [
            'name' => 'Gold Plated Charm Bracelet',
            'slug' => 'gold-plated-charm-bracelet',
            'product_type' => 'configurable',
            'brand_id' => $brands['glamour-jewels'],
            'main_category_id' => $categories['bracelets'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Beautiful gold plated charm bracelet with multiple pendants.',
            'description' => $this->getHTMLDescription('Gold Plated Charm Bracelet', 'charm', 'bracelets'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 22.5,
            'length' => 7.5,
            'width' => 2.0,
            'height' => 0.6,
            'meta_title' => 'Gold Charm Bracelet - Fashion Bracelet',
            'meta_description' => 'Beautiful gold plated charm bracelet with multiple pendant charms. Adjustable length.',
            'meta_keywords' => 'charm bracelet, gold bracelet, adjustable bracelet, fashion bracelet',
            'canonical_url' => '/jewelry/bracelets/gold-plated-charm-bracelet',
            'product_code' => 'BRAC-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Silver Bangle Bracelet with Engraving',
            'slug' => 'silver-bangle-bracelet-engraving',
            'product_type' => 'simple', // Changed to simple
            'brand_id' => $brands['sparkle-shine'],
            'main_category_id' => $categories['bracelets'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Elegant silver bangle bracelet with beautiful engraving.',
            'description' => $this->getHTMLDescription('Silver Bangle Bracelet with Engraving', 'bangle', 'bracelets'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 18.7,
            'length' => 7.0,
            'width' => 0.8,
            'height' => 0.8,
            'meta_title' => 'Silver Bangle Bracelet with Engraving',
            'meta_description' => 'Elegant silver bangle bracelet with beautiful engraved pattern. Perfect for daily wear.',
            'meta_keywords' => 'bangle bracelet, silver bracelet, engraved bracelet, daily wear',
            'canonical_url' => '/jewelry/bracelets/silver-bangle-bracelet-engraving',
            'product_code' => 'BRAC-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Rose Gold Cuff Bracelet with Crystals',
            'slug' => 'rose-gold-cuff-bracelet-crystals',
            'product_type' => 'configurable',
            'brand_id' => $brands['elegance-craft'],
            'main_category_id' => $categories['bracelets'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Stylish rose gold cuff bracelet with sparkling crystals.',
            'description' => $this->getHTMLDescription('Rose Gold Cuff Bracelet with Crystals', 'cuff', 'bracelets'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 28.3,
            'length' => 7.2,
            'width' => 3.5,
            'height' => 0.5,
            'meta_title' => 'Rose Gold Cuff Bracelet - Statement Jewelry',
            'meta_description' => 'Stylish rose gold cuff bracelet with sparkling crystal details. Makes a bold fashion statement.',
            'meta_keywords' => 'cuff bracelet, rose gold bracelet, crystal bracelet, statement bracelet',
            'canonical_url' => '/jewelry/bracelets/rose-gold-cuff-bracelet-crystals',
            'product_code' => 'BRAC-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== BANGLES (3 products) ====================
        $products[] = [
            'name' => 'Gold Plated Traditional Bangles Set',
            'slug' => 'gold-plated-traditional-bangles-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['glamour-jewels'],
            'main_category_id' => $categories['bangles'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Set of 4 gold plated traditional bangles with designs.',
            'description' => $this->getHTMLDescription('Gold Plated Traditional Bangles Set', 'traditional', 'bangles'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 85.7,
            'length' => 7.0,
            'width' => 0.8,
            'height' => 0.8,
            'meta_title' => 'Gold Traditional Bangles Set - Ethnic Jewelry',
            'meta_description' => 'Set of 4 gold plated traditional bangles with beautiful ethnic designs. Perfect for weddings.',
            'meta_keywords' => 'bangles set, gold bangles, traditional bangles, wedding jewelry',
            'canonical_url' => '/jewelry/bangles/gold-plated-traditional-bangles-set',
            'product_code' => 'BANG-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Silver Stackable Thin Bangles',
            'slug' => 'silver-stackable-thin-bangles',
            'product_type' => 'simple', // Changed to simple
            'brand_id' => $brands['sparkle-shine'],
            'main_category_id' => $categories['bangles'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Set of 6 silver thin bangles perfect for stacking.',
            'description' => $this->getHTMLDescription('Silver Stackable Thin Bangles', 'stackable', 'bangles'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 42.3,
            'length' => 6.8,
            'width' => 0.3,
            'height' => 0.3,
            'meta_title' => 'Silver Stackable Thin Bangles - Minimalist',
            'meta_description' => 'Set of 6 silver thin bangles perfect for creating stacked looks. Minimalist design.',
            'meta_keywords' => 'thin bangles, stackable bangles, silver bangles, minimalist bangles',
            'canonical_url' => '/jewelry/bangles/silver-stackable-thin-bangles',
            'product_code' => 'BANG-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Rose Gold Textured Bangles Set',
            'slug' => 'rose-gold-textured-bangles-set',
            'product_type' => 'configurable',
            'brand_id' => $brands['elegance-craft'],
            'main_category_id' => $categories['bangles'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Set of 3 rose gold bangles with unique textures.',
            'description' => $this->getHTMLDescription('Rose Gold Textured Bangles Set', 'textured', 'bangles'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 65.8,
            'length' => 7.2,
            'width' => 0.6,
            'height' => 0.6,
            'meta_title' => 'Rose Gold Textured Bangles Set - Modern Jewelry',
            'meta_description' => 'Set of 3 rose gold bangles with unique textured designs. Modern and stylish.',
            'meta_keywords' => 'textured bangles, rose gold bangles, modern bangles, fashion bangles',
            'canonical_url' => '/jewelry/bangles/rose-gold-textured-bangles-set',
            'product_code' => 'BANG-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== PENDANTS (3 products) ====================
        $products[] = [
            'name' => 'Gold Plated Heart Pendant with Chain',
            'slug' => 'gold-plated-heart-pendant-chain',
            'product_type' => 'simple',
            'brand_id' => $brands['glamour-jewels'],
            'main_category_id' => $categories['pendant'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Beautiful gold plated heart pendant with matching chain.',
            'description' => $this->getHTMLDescription('Gold Plated Heart Pendant with Chain', 'heart', 'pendant'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 12.5,
            'length' => 2.5,
            'width' => 2.5,
            'height' => 0.3,
            'meta_title' => 'Gold Heart Pendant with Chain - Romantic Jewelry',
            'meta_description' => 'Beautiful gold plated heart pendant with matching chain. Perfect gift for loved ones.',
            'meta_keywords' => 'heart pendant, gold pendant, pendant with chain, romantic jewelry',
            'canonical_url' => '/jewelry/pendant/gold-plated-heart-pendant-chain',
            'product_code' => 'PEND-001',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Silver Tree of Life Pendant',
            'slug' => 'silver-tree-of-life-pendant',
            'product_type' => 'simple',
            'brand_id' => $brands['sparkle-shine'],
            'main_category_id' => $categories['pendant'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Meaningful silver Tree of Life pendant with crystals.',
            'description' => $this->getHTMLDescription('Silver Tree of Life Pendant', 'tree', 'pendant'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 15.8,
            'length' => 3.2,
            'width' => 2.8,
            'height' => 0.4,
            'meta_title' => 'Silver Tree of Life Pendant - Spiritual Jewelry',
            'meta_description' => 'Meaningful silver Tree of Life pendant with crystal accents. Symbol of growth and connection.',
            'meta_keywords' => 'tree of life pendant, silver pendant, spiritual jewelry, meaningful pendant',
            'canonical_url' => '/jewelry/pendant/silver-tree-of-life-pendant',
            'product_code' => 'PEND-002',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $products[] = [
            'name' => 'Rose Gold Initial Pendant',
            'slug' => 'rose-gold-initial-pendant',
            'product_type' => 'configurable',
            'brand_id' => $brands['elegance-craft'],
            'main_category_id' => $categories['pendant'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Personalized rose gold initial pendant with diamond-cut letter.',
            'description' => $this->getHTMLDescription('Rose Gold Initial Pendant', 'initial', 'pendant'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 10.2,
            'length' => 2.0,
            'width' => 1.5,
            'height' => 0.2,
            'meta_title' => 'Rose Gold Initial Pendant - Personalized Jewelry',
            'meta_description' => 'Personalized rose gold initial pendant with beautiful diamond-cut letter. Perfect personalized gift.',
            'meta_keywords' => 'initial pendant, personalized pendant, rose gold pendant, custom jewelry',
            'canonical_url' => '/jewelry/pendant/rose-gold-initial-pendant',
            'product_code' => 'PEND-003',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==================== INSERT PRODUCTS AND CREATE VARIANTS ====================

        $productIds = [];

        foreach ($products as $index => $productData) {
            // Insert product
            $productId = DB::table('products')->insertGetId($productData);
            $productIds[] = $productId;

            // Create variants based on product type
            $this->createProductVariants(
                $productId,
                $productData,
                $categories,
                $colorValues,
                $sizeValues,
                $chainLengthValues,
                $ringSizeValues
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
    $chainLengthValues,
    $ringSizeValues
) {
    $categorySlug = collect($categories)
        ->flip()
        ->get($productData['main_category_id']);

    if (!$categorySlug) {
        return;
    }

    $variants = [];

    switch ($categorySlug) {
        case 'earrings':
            $variants = $this->createEarringVariants($productId, $colorValues, $sizeValues);
            break;

        case 'necklaces':
            $variants = $this->createNecklaceVariants($productId, $colorValues, $chainLengthValues);
            break;

        case 'rings':
            $variants = $this->createRingVariants($productId, $colorValues, $ringSizeValues);
            break;

        case 'bracelets':
            $variants = $this->createBraceletVariants($productId, $colorValues, $sizeValues);
            break;

        case 'bangles':
            $variants = $this->createBangleVariants($productId, $colorValues, $sizeValues);
            break;

        case 'pendant':
            $variants = $this->createPendantVariants($productId, $colorValues);
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


    private function createSimpleVariant($productId, $productData, $categorySlug)
    {
        $basePrice = $this->getBasePriceForCategory($categorySlug);

        $variant = [
            'variant' => [
                'product_id' => $productId,
                'sku' => $productData['product_code'] . '-001',
                'combination_hash' => null, // NULL for simple products
                'price' => $basePrice,
                'compare_price' => round($basePrice * 1.3, 2), // 30% higher compare price
                'cost_price' => round($basePrice * 0.5, 2), // 50% cost
                'stock_quantity' => rand(50, 200),
                'reserved_quantity' => 0,
                'stock_status' => 'in_stock',
                'is_default' => 1, // Only variant = default
                'status' => 1,
                'weight' => $productData['weight'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            'attributes' => [] // Simple products have no variant attributes
        ];

        return [$variant];
    }

    private function getBasePriceForCategory($categorySlug)
    {
        $prices = [
            'earrings' => rand(15, 45),
            'necklaces' => rand(25, 80),
            'rings' => rand(20, 60),
            'bracelets' => rand(22, 65),
            'bangles' => rand(35, 120),
            'pendant' => rand(18, 55),
        ];

        return $prices[$categorySlug] ?? 20;
    }

    private function createEarringVariants($productId, $colorValues, $sizeValues)
    {
        $variants = [];
        $colors = ['Gold', 'Rose Gold', 'Silver'];
        $sizes = ['Small', 'Medium'];
        $skuBase = 'EARR-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => rand(15, 45),
                        'compare_price' => rand(25, 60),
                        'cost_price' => rand(8, 20),
                        'stock_quantity' => rand(50, 200),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(5, 20),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'size')->first()->id, 'attribute_value_id' => $sizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createNecklaceVariants($productId, $colorValues, $chainLengthValues)
    {
        $variants = [];
        $colors = ['Gold', 'Rose Gold', 'Silver'];
        $lengths = ['16', '18', '20'];
        $skuBase = 'NECK-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($lengths as $length) {
                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $length);

                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => rand(25, 80),
                        'compare_price' => rand(35, 100),
                        'cost_price' => rand(12, 35),
                        'stock_quantity' => rand(30, 150),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(15, 40),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'chain_length')->first()->id, 'attribute_value_id' => $chainLengthValues[$length]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createRingVariants($productId, $colorValues, $ringSizeValues)
    {
        $variants = [];
        $colors = ['Gold', 'Rose Gold', 'Silver'];
        $sizes = [6, 7, 8];
        $skuBase = 'RING-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => rand(20, 60),
                        'compare_price' => rand(30, 80),
                        'cost_price' => rand(10, 25),
                        'stock_quantity' => rand(40, 180),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(5, 15),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'ring_size')->first()->id, 'attribute_value_id' => $ringSizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createBraceletVariants($productId, $colorValues, $sizeValues)
    {
        $variants = [];
        $colors = ['Gold', 'Silver', 'Rose Gold'];
        $sizes = ['Small', 'Medium'];
        $skuBase = 'BRAC-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => rand(22, 65),
                        'compare_price' => rand(32, 85),
                        'cost_price' => rand(11, 30),
                        'stock_quantity' => rand(35, 160),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(15, 35),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'size')->first()->id, 'attribute_value_id' => $sizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createBangleVariants($productId, $colorValues, $sizeValues)
    {
        $variants = [];
        $colors = ['Gold', 'Silver'];
        $sizes = ['Small', 'Medium'];
        $skuBase = 'BANG-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
                $combinationHash = md5($color . $size);

                $variants[] = [
                    'variant' => [
                        'product_id' => $productId,
                        'sku' => $sku,
                        'combination_hash' => $combinationHash,
                        'price' => rand(35, 120),
                        'compare_price' => rand(45, 150),
                        'cost_price' => rand(18, 50),
                        'stock_quantity' => rand(20, 100),
                        'reserved_quantity' => 0,
                        'stock_status' => 'in_stock',
                        'is_default' => ($variantCount === 1) ? 1 : 0,
                        'status' => 1,
                        'weight' => rand(30, 100),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    'attributes' => [
                        ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                        ['attribute_id' => DB::table('attributes')->where('code', 'size')->first()->id, 'attribute_value_id' => $sizeValues[$size]],
                    ]
                ];
                $variantCount++;
            }
        }

        return $variants;
    }

    private function createPendantVariants($productId, $colorValues)
    {
        $variants = [];
        $colors = ['Gold', 'Silver', 'Rose Gold'];
        $skuBase = 'PEND-' . $productId;
        $variantCount = 1;

        foreach ($colors as $color) {
            $sku = $skuBase . '-' . str_pad($variantCount, 3, '0', STR_PAD_LEFT);
            $combinationHash = md5($color);

            $variants[] = [
                'variant' => [
                    'product_id' => $productId,
                    'sku' => $sku,
                    'combination_hash' => $combinationHash,
                    'price' => rand(18, 55),
                    'compare_price' => rand(28, 70),
                    'cost_price' => rand(9, 25),
                    'stock_quantity' => rand(45, 200),
                    'reserved_quantity' => 0,
                    'stock_status' => 'in_stock',
                    'is_default' => ($variantCount === 1) ? 1 : 0,
                    'status' => 1,
                    'weight' => rand(8, 20),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                'attributes' => [
                    ['attribute_id' => DB::table('attributes')->where('code', 'color')->first()->id, 'attribute_value_id' => $colorValues[$color]],
                ]
            ];
            $variantCount++;
        }

        return $variants;
    }

    private function addVariantImages($variantId, $productId)
    {
        // Use media IDs 10-20 for variant images
        $mediaIds = [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

        // Select 2-3 random media IDs for this variant
        $selectedIndices = array_rand($mediaIds, rand(2, 3));
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

        // Common specifications for all jewelry
        $baseSpecs = [
            [
                'specification_id' => $specs['base_metal'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['base_metal']], 'Zinc Alloy'),
            ],
            [
                'specification_id' => $specs['plating_type'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['plating_type']], null),
            ],
            [
                'specification_id' => $specs['plating_thickness'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['plating_thickness']], 'Medium (1-2 microns)'),
            ],
            [
                'specification_id' => $specs['gender'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['gender']], 'Women'),
            ],
            [
                'specification_id' => $specs['occasion'],
                'specification_value_id' => null,
                'custom_value' => 'Party, Casual, Wedding',
            ],
            [
                'specification_id' => $specs['care_instructions'],
                'specification_value_id' => null,
                'custom_value' => 'Avoid contact with water, perfume, and chemicals. Store in a dry place. Clean with soft cloth.',
            ],
            [
                'specification_id' => $specs['hypoallergenic'],
                'specification_value_id' => null,
                'custom_value' => 'Yes',
            ],
            [
                'specification_id' => $specs['water_resistance'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['water_resistance']], 'Not Water Resistant'),
            ],
        ];

        // Add stone type if product name contains stone-related words
        if (strpos($productData['name'], 'Crystal') !== false ||
            strpos($productData['name'], 'CZ') !== false ||
            strpos($productData['name'], 'Pearl') !== false ||
            strpos($productData['name'], 'Gemstone') !== false) {

            $stoneType = strpos($productData['name'], 'Pearl') !== false ? 'Synthetic Pearl' : 'Crystal';

            $baseSpecs[] = [
                'specification_id' => $specs['stone_type'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['stone_type']], $stoneType),
            ];

            if ($stoneType === 'Crystal') {
                $baseSpecs[] = [
                    'specification_id' => $specs['stone_color'],
                    'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['stone_color']], 'Clear'),
                ];
            }
        }

        // Add closure type for necklaces and bracelets
        if (strpos($productData['slug'], 'necklace') !== false ||
            strpos($productData['slug'], 'bracelet') !== false ||
            strpos($productData['slug'], 'bangle') !== false) {

            $baseSpecs[] = [
                'specification_id' => $specs['closure_type'],
                'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['closure_type']], 'Lobster Clasp'),
            ];
        }

        // Add weight range
        $weight = $productData['weight'];
        $weightRange = ($weight < 10) ? 'Light (0-10g)' : (($weight < 30) ? 'Medium (10-30g)' : 'Heavy (30g+)');
        $baseSpecs[] = [
            'specification_id' => $specs['weight_range'],
            'specification_value_id' => $this->getRandomSpecValueId($specValues[$specs['weight_range']], $weightRange),
        ];

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
            $productTags = ['Fashion Jewelry', 'Imitation'];

            // Material tags
            if (strpos($product->name, 'Gold') !== false) {
                $productTags[] = 'Gold Plated';
            }
            if (strpos($product->name, 'Silver') !== false) {
                $productTags[] = 'Silver Plated';
            }
            if (strpos($product->name, 'Crystal') !== false || strpos($product->name, 'CZ') !== false) {
                $productTags[] = 'Crystal';
            }
            if (strpos($product->name, 'Pearl') !== false) {
                $productTags[] = 'Pearl';
            }

            // Occasion tags
            if (strpos($product->name, 'Party') !== false ||
                strpos($product->name, 'Cocktail') !== false ||
                strpos($product->name, 'Statement') !== false) {
                $productTags[] = 'Party Wear';
            } else {
                $productTags[] = 'Daily Wear';
            }

            // Style tags
            if (strpos($product->name, 'Statement') !== false ||
                strpos($product->name, 'Cuff') !== false ||
                strpos($product->name, 'Traditional') !== false) {
                $productTags[] = 'Statement';
            } else {
                $productTags[] = 'Minimalist';
            }

            // Add trendy tag for new products
            if (strpos($product->name, 'Rose Gold') !== false ||
                strpos($product->name, 'Layered') !== false ||
                strpos($product->name, 'Textured') !== false) {
                $productTags[] = 'Trendy';
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

            // Add 3-4 related products (random, but not itself)
            $allProductIds = $products->pluck('id')->toArray();
            $relatedCount = min(4, count($allProductIds) - 1);
            $possibleRelatedIds = array_diff($allProductIds, [$product->id]);
            $relatedIds = array_rand(array_flip($possibleRelatedIds), $relatedCount);

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

    private function getHTMLDescription($productName, $type, $category)
    {
        $descriptions = [
            'earrings' => [
                'stud' => "<h2>Exquisite Stud Earrings</h2>
                    <p>These beautiful stud earrings feature high-quality plating that ensures long-lasting shine and durability. The crystal stones are carefully set to maximize sparkle and brilliance.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Hypoallergenic nickel-free base metal</li>
                        <li>Secure push-back closure for comfort</li>
                        <li>Perfect for daily wear and special occasions</li>
                        <li>Lightweight design for all-day comfort</li>
                        <li>Comes in a protective jewelry pouch</li>
                    </ul>
                    <h3>Care Instructions:</h3>
                    <p>Store in a dry place away from moisture. Avoid contact with perfumes, lotions, and chemicals. Clean with a soft, dry cloth.</p>",
                'hoop' => "<h2>Classic Hoop Earrings</h2>
                    <p>Timeless hoop earrings with elegant pearl accents that add sophistication to any outfit. The smooth finish and perfect circular shape create a classic look.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Adjustable hinge for easy wear</li>
                        <li>Lobster clasp closure for security</li>
                        <li>Medium weight for comfortable all-day wear</li>
                        <li>Pearl accents add elegant detail</li>
                        <li>Versatile design for multiple occasions</li>
                    </ul>",
                'dangler' => "<h2>Elegant Dangler Earrings</h2>
                    <p>Make a statement with these beautiful dangler earrings featuring sparkling crystals. The delicate design moves gracefully with every step.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>French wire design for comfortable wear</li>
                        <li>Multiple crystal settings for maximum sparkle</li>
                        <li>Lightweight construction</li>
                        <li>Perfect length for noticeable but comfortable wear</li>
                        <li>Ideal for parties and special events</li>
                    </ul>"
            ],
            'necklaces' => [
                'choker' => "<h2>Elegant Choker Necklace</h2>
                    <p>This beautiful choker necklace sits perfectly at the base of the neck, featuring an exquisite pendant that catches the light beautifully.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Adjustable length for perfect fit</li>
                        <li>Secure lobster clasp closure</li>
                        <li>Intricate pendant design</li>
                        <li>Comfortable wear for extended periods</li>
                        <li>Perfect for formal occasions</li>
                    </ul>",
                'chain' => "<h2>Classic Chain Necklace</h2>
                    <p>A timeless chain necklace featuring sparkling cubic zirconia stones that mimic the brilliance of diamonds at a fraction of the cost.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Durable chain links</li>
                        <li>Secure spring ring clasp</li>
                        <li>Evenly spaced stone settings</li>
                        <li>Perfect for layering or wearing alone</li>
                        <li>Great for daily wear</li>
                    </ul>",
                'layered' => "<h2>Trendy Layered Necklace Set</h2>
                    <p>Create fashionable looks with this complete layered necklace set featuring multiple chains of different lengths and styles.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Three separate chains for versatile styling</li>
                        <li>Different pendant styles on each chain</li>
                        <li>Adjustable clasps on each piece</li>
                        <li>Can be worn together or separately</li>
                        <li>Complete jewelry look in one set</li>
                    </ul>"
            ],
            'rings' => [
                'cocktail' => "<h2>Statement Cocktail Ring</h2>
                    <p>Make a bold fashion statement with this exquisite cocktail ring. Perfect for parties and special occasions.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Eye-catching crystal cluster design</li>
                        <li>Comfortable band for all-day wear</li>
                        <li>Adjustable sizing available</li>
                        <li>Perfect for making a statement</li>
                        <li>Comes in premium gift packaging</li>
                    </ul>",
                'stackable' => "<h2>Stackable Band Rings Set</h3>
                    <p>Create your own unique look with this set of minimalist band rings that can be stacked and mixed.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Set of 3 thin bands</li>
                        <li>Minimalist design for versatility</li>
                        <li>Perfect for layering</li>
                        <li>Great for daily wear</li>
                        <li>Mix and match with other rings</li>
                    </ul>",
                'solitaire' => "<h2>Elegant Solitaire Ring</h2>
                    <p>Classic solitaire design with a beautiful cubic zirconia stone that mimics the look of a diamond.</p>
                    <h3>Features:</h3>
                    <ul>
                        <li>Classic solitaire setting</li>
                        <li>High-quality cubic zirconia stone</li>
                        <li>Comfort fit band</li>
                        <li>Perfect for special occasions</li>
                        <li>Available in multiple sizes</li>
                    </ul>"
            ],
            // Add other categories similarly...
        ];

        return $descriptions[$category][$type] ?? "<h2>{$productName}</h2><p>Beautiful {$category} made with high-quality imitation materials. Perfect for adding elegance to any outfit.</p>";
    }
}
