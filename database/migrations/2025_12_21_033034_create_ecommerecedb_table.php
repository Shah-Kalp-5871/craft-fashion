<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // First, ensure we're using InnoDB for all tables
        DB::statement('SET default_storage_engine=INNODB');

        // ==================== CORE TABLES (No dependencies) ====================

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('disk', 50)->default('public');
            $table->string('mime_type', 100)->nullable();
            $table->enum('file_type', ['image', 'video', 'document', 'pdf', 'archive']);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->json('thumbnails')->nullable();
            $table->json('metadata')->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->string('uploader_type', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('file_type');
            $table->index('created_at');
            $table->index(['uploader_type', 'uploaded_by']);
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'manager', 'editor'])->default('admin');
            $table->tinyInteger('status')->default(1);
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('role');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150)->unique()->nullable();
            $table->string('mobile', 20)->unique()->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('email_verified_at');
            $table->index('mobile_verified_at');
        });

        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->string('password_hash');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_type', 'user_id', 'created_at']);
        });

        // ==================== CUSTOMER SEGMENTS ====================

        Schema::create('customer_segments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->json('conditions')->nullable();
            $table->integer('customer_count')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('customer_segment_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_segment_id');
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['customer_id', 'customer_segment_id']);
            $table->index('customer_id');
        });

        // ==================== CATALOG STRUCTURE ====================

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('show_in_nav')->default(1);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('parent_id');
            $table->index('featured');
            $table->index('show_in_nav');
            $table->index('slug');
        });

        Schema::create('category_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ancestor_id');
            $table->unsignedBigInteger('descendant_id');
            $table->integer('depth')->default(0);

            $table->unique(['ancestor_id', 'descendant_id']);
            $table->index('descendant_id');
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('slug');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('slug');
        });

        // ==================== ATTRIBUTE SYSTEM ====================

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->enum('type', ['select', 'color', 'image', 'text'])->default('select');
            $table->tinyInteger('is_variant')->default(1);
            $table->tinyInteger('is_filterable')->default(1);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_variant');
            $table->index('status');
            $table->index('sort_order');
            $table->index('is_filterable');
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->string('value', 100);
            $table->string('label', 100);
            $table->string('color_code', 20)->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('attribute_id');
            $table->index('status');
            $table->unique(['attribute_id', 'value']);
        });

        Schema::create('category_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('attribute_id');
            $table->tinyInteger('is_required')->default(0);
            $table->tinyInteger('is_filterable')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['category_id', 'attribute_id']);
            $table->index(['category_id', 'is_required']);
        });

        // ==================== SPECIFICATION SYSTEM ====================

        Schema::create('specification_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('sort_order');
        });

        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->enum('input_type', ['select', 'text', 'textarea', 'multiselect', 'radio', 'checkbox'])->default('select');
            $table->tinyInteger('is_required')->default(0);
            $table->tinyInteger('is_filterable')->default(0);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('input_type');
            $table->index('is_filterable');
        });

        Schema::create('specification_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specification_id');
            $table->string('value', 100);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('specification_id');
            $table->index('status');
            $table->unique(['specification_id', 'value']);
        });

        Schema::create('spec_group_specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spec_group_id');
            $table->unsignedBigInteger('specification_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['spec_group_id', 'specification_id']);
            $table->index('spec_group_id');
        });

        Schema::create('category_spec_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('spec_group_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['category_id', 'spec_group_id']);
            $table->index('category_id');
        });

        // ==================== TAX SYSTEM ====================

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name', 50);
            $table->string('symbol', 10);
            $table->decimal('exchange_rate', 12, 6)->default(1.000000);
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->integer('decimal_places')->default(2);
            $table->timestamps();

            $table->index('is_default');
            $table->index('is_active');
        });

        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_default');
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_class_id');
            $table->string('name', 100);
            $table->string('country_code', 2)->nullable();
            $table->string('state_code', 10)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->decimal('rate', 8, 4);
            $table->tinyInteger('is_active')->default(1);
            $table->integer('priority')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tax_class_id');
            $table->index('is_active');
            $table->index(['country_code', 'state_code']);
        });

        // ==================== SHIPPING & PAYMENTS ====================

        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->json('config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('sort_order');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->tinyInteger('is_active')->default(1);
            $table->json('config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('sort_order');
        });

        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->json('countries')->nullable();
            $table->json('states')->nullable();
            $table->json('zip_codes')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_zone_id');
            $table->unsignedBigInteger('shipping_method_id');
            $table->decimal('min_weight', 10, 3)->nullable();
            $table->decimal('max_weight', 10, 3)->nullable();
            $table->decimal('min_price', 12, 2)->nullable();
            $table->decimal('max_price', 12, 2)->nullable();
            $table->decimal('charge', 12, 2);
            $table->decimal('free_shipping_threshold', 12, 2)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index(['shipping_zone_id', 'shipping_method_id']);
            $table->index('is_active');
        });

        // ==================== PRODUCTS & VARIANTS ====================

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->enum('product_type', ['simple', 'configurable', 'bundle', 'virtual', 'downloadable'])->default('simple');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('main_category_id')->nullable();
            $table->unsignedBigInteger('tax_class_id')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive', 'out_of_stock'])->default('draft');
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_new')->default(0);
            $table->tinyInteger('is_bestseller')->default(0);
            $table->decimal('weight', 10, 3)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('product_code', 100)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('is_featured');
            $table->index('is_new');
            $table->index('is_bestseller');
            $table->index('brand_id');
            $table->index('product_type');
            $table->index('slug');
            $table->index('product_code');
            $table->fullText(['name', 'short_description', 'description']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku', 100)->unique();
            $table->string('combination_hash', 100)->nullable()->unique()->comment('NULL for simple products');
            $table->decimal('price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable()->comment('Price to show as "was" price');
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reserved_quantity')->default(0)->comment('Quantity reserved in carts');
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder', 'preorder'])->default('in_stock');
            $table->tinyInteger('is_default')->default(0)->comment('Default variant to show first');
            $table->tinyInteger('status')->default(1);
            $table->decimal('weight', 10, 3)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('status');
            $table->index('stock_status');
            $table->index('combination_hash');
            $table->index('sku');
            $table->index('is_default');
            $table->index(['product_id', 'is_default']);
        });

        Schema::create('variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id');
            $table->timestamps();

            $table->unique(['variant_id', 'attribute_id']);
            $table->index('variant_id');
            $table->index('attribute_value_id');
        });

        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('specification_id');
            $table->unsignedBigInteger('specification_value_id')->nullable();
            $table->text('custom_value')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'specification_id']);
            $table->index('product_id');
        });

        // ==================== PRODUCT MEDIA ====================

        Schema::create('variant_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('media_id');
            $table->tinyInteger('is_primary')->default(0)->comment('Primary image for this variant');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['variant_id', 'media_id']);
            $table->index('variant_id');
            $table->index(['variant_id', 'is_primary']);
        });

        // ==================== PRODUCT RELATIONSHIPS ====================

        Schema::create('category_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->tinyInteger('is_primary')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->primary(['product_id', 'category_id']);
            $table->index('category_id');
            $table->index(['product_id', 'is_primary']);
        });

        Schema::create('product_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->primary(['product_id', 'tag_id']);
        });

        Schema::create('related_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('related_product_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'related_product_id']);
            $table->index('product_id');
        });

        Schema::create('cross_sell_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('cross_sell_product_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'cross_sell_product_id']);
            $table->index('product_id');
        });

        Schema::create('upsell_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('upsell_product_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'upsell_product_id']);
            $table->index('product_id');
        });

        // ==================== PRICE MANAGEMENT ====================

        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->decimal('old_price', 12, 2);
            $table->decimal('new_price', 12, 2);
            $table->decimal('old_compare_price', 12, 2)->nullable();
            $table->decimal('new_compare_price', 12, 2)->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('change_reason', 255)->nullable();
            $table->timestamp('effective_from')->useCurrent();
            $table->timestamp('effective_to')->nullable();
            $table->timestamps();

            $table->index('product_variant_id');
            $table->index('effective_from');
            $table->index('changed_by');
        });

        Schema::create('tier_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('min_quantity');
            $table->integer('max_quantity')->nullable();
            $table->decimal('price', 12, 2);
            $table->enum('customer_group', ['all', 'guest', 'registered', 'wholesale'])->default('all');
            $table->unsignedBigInteger('customer_segment_id')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['product_variant_id', 'min_quantity', 'customer_group', 'customer_segment_id'],
                'tier_prices_unique'
            );
            $table->index('product_variant_id');
            $table->index(['starts_at', 'ends_at']);
        });

        // ==================== CUSTOMER DATA ====================

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->enum('type', ['shipping', 'billing', 'both'])->default('shipping');
            $table->string('name', 100);
            $table->string('mobile', 20);
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 2);
            $table->string('pincode', 20);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();

            $table->index(['customer_id', 'type']);
            $table->index(['customer_id', 'is_default']);
            $table->index(['country', 'state', 'city']);
        });

        // ==================== LOYALTY & REWARDS ====================

        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->decimal('points_per_currency', 10, 2)->default(1);
            $table->integer('signup_bonus')->default(0);
            $table->integer('first_purchase_bonus')->default(0);
            $table->decimal('min_redeemable_points', 10, 2)->default(100);
            $table->decimal('point_value', 10, 2)->default(0.01);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('status');
            $table->index(['starts_at', 'ends_at']);
        });

        Schema::create('customer_loyalty', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('loyalty_program_id');
            $table->decimal('total_points', 12, 2)->default(0);
            $table->decimal('available_points', 12, 2)->default(0);
            $table->decimal('used_points', 12, 2)->default(0);
            $table->decimal('expired_points', 12, 2)->default(0);
            $table->integer('tier_level')->default(1);
            $table->timestamps();

            $table->unique(['customer_id', 'loyalty_program_id']);
            $table->index('customer_id');
        });

        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_loyalty_id');
            $table->enum('type', ['earn', 'redeem', 'expire', 'adjust', 'bonus']);
            $table->decimal('points', 12, 2);
            $table->decimal('balance', 12, 2);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('customer_loyalty_id');
            $table->index(['reference_type', 'reference_id']);
            $table->index('type');
        });

        // ==================== WISHLISTS ====================

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('name', 50)->default('Default');
            $table->tinyInteger('is_public')->default(0);
            $table->timestamps();

            $table->index('customer_id');
            $table->unique(['customer_id', 'name']);
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wishlist_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->timestamps();

            $table->unique(['wishlist_id', 'product_variant_id']);
            $table->index('wishlist_id');
        });

        // ==================== OFFERS & COUPONS (Create before cart/orders) ====================

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->nullable()->unique();
            $table->tinyInteger('status')->default(1);

            $table->enum('offer_type', [
                'percentage',
                'fixed',
                'bogo',
                'buy_x_get_y',
                'free_shipping',
                'tiered'
            ]);

            $table->decimal('discount_value', 12, 2)->nullable();
            $table->unsignedInteger('buy_qty')->nullable();
            $table->unsignedInteger('get_qty')->nullable();

            $table->decimal('min_cart_amount', 12, 2)->nullable();
            $table->decimal('max_cart_amount', 12, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('uses_per_customer')->nullable();
            $table->unsignedInteger('used_count')->default(0);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->tinyInteger('is_auto_apply')->default(1);
            $table->tinyInteger('is_stackable')->default(0);
            $table->tinyInteger('is_exclusive')->default(0);

            $table->unsignedBigInteger('customer_segment_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('offer_type');
            $table->index('code');
            $table->index(['starts_at', 'ends_at']);
            $table->index('is_auto_apply');
        });

        Schema::create('offer_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();

            $table->index('offer_id');
            $table->index('customer_id');
            $table->index('order_id');
        });

        Schema::create('offer_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->timestamps();

            $table->unique(['offer_id', 'product_variant_id']);
            $table->index('offer_id');
        });

        Schema::create('offer_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->unique(['offer_id', 'category_id']);
            $table->index('offer_id');
        });

        Schema::create('offer_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('reward_product_id');
            $table->unsignedBigInteger('reward_variant_id')->nullable();
            $table->unsignedInteger('reward_qty')->default(1);
            $table->tinyInteger('same_as_buy_product')->default(0);
            $table->timestamps();

            $table->index('offer_id');
        });

        // ==================== CART ====================

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->enum('status', ['active', 'abandoned', 'converted'])->default('active');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->timestamp('abandoned_at')->nullable();
            $table->timestamps();

            $table->unique(['customer_id', 'session_id']);
            $table->index('status');
            $table->index('abandoned_at');
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();

            $table->unique(['cart_id', 'product_variant_id']);
            $table->index('cart_id');
            $table->index('offer_id');
        });

        // ==================== ORDER NUMBER SEQUENCE ====================

        Schema::create('order_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 10);
            $table->integer('last_number')->default(0);
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->timestamps();

            $table->unique(['prefix', 'year', 'month']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'returned'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->enum('shipping_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->decimal('loyalty_points_used', 12, 2)->default(0);
            $table->decimal('loyalty_points_earned', 12, 2)->default(0);
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('cancellation_reason', 255)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_number');
            $table->index('customer_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('shipping_status');
            $table->index('offer_id');
            $table->index('created_at');
            $table->index('grand_total');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->string('product_name', 200);
            $table->string('sku', 100);
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->decimal('total', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->json('attributes')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->decimal('loyalty_points', 12, 2)->default(0);
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_variant_id');
            $table->index('offer_id');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'returned']);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('status');
            $table->index('created_at');
        });

        // ==================== PAYMENTS ====================

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('transaction_id', 100)->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('payment_gateway', 50)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'partially_refunded'])->default('pending');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('payment_gateway');
        });

        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('attempt_id', 100)->unique();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['initiated', 'failed', 'success', 'abandoned'])->default('initiated');
            $table->json('gateway_response')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('attempt_id');
            $table->index('status');
        });

        // ==================== SHIPMENTS ====================

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('tracking_number', 100)->nullable()->unique();
            $table->string('carrier', 50)->nullable();
            $table->string('carrier_service', 50)->nullable();
            $table->enum('status', ['pending', 'processing', 'shipped', 'in_transit', 'delivered', 'cancelled', 'returned'])->default('pending');
            $table->decimal('weight', 10, 3)->nullable();
            $table->json('dimensions')->nullable();
            $table->json('shipping_label')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('delivered_to', 100)->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('tracking_number');
            $table->index('status');
            $table->index('carrier');
        });

        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->unsignedBigInteger('order_item_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['shipment_id', 'order_item_id']);
            $table->index('shipment_id');
        });

        // ==================== RETURNS & REFUNDS ====================

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 50)->unique();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('status', ['requested', 'approved', 'rejected', 'received', 'processed', 'completed'])->default('requested');
            $table->enum('type', ['refund', 'replacement', 'store_credit'])->default('refund');
            $table->string('reason', 255);
            $table->text('notes')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->unsignedBigInteger('refund_payment_id')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('return_number');
            $table->index('order_id');
            $table->index('customer_id');
            $table->index('status');
        });

        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('order_item_id');
            $table->integer('quantity');
            $table->enum('condition', ['unopened', 'opened', 'damaged', 'defective'])->default('unopened');
            $table->text('reason')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->timestamps();

            $table->index('return_id');
            $table->index('order_item_id');
        });

        // ==================== STOCK MANAGEMENT ====================

        Schema::create('stock_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->enum('change_type', ['increase', 'decrease', 'adjustment', 'initial', 'transfer', 'return', 'order', 'reservation', 'release']);
            $table->integer('quantity');
            $table->integer('old_quantity')->nullable();
            $table->integer('new_quantity')->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('source_type', 50)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('product_variant_id');
            $table->index(['source_type', 'source_id']);
            $table->index('created_at');
            $table->index('change_type');
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('address', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('pincode', 20)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('is_default');
            $table->index('is_active');
        });

        Schema::create('warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_variant_id']);
            $table->index('warehouse_id');
            $table->index('product_variant_id');
        });

        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number', 50)->unique();
            $table->unsignedBigInteger('from_warehouse_id');
            $table->unsignedBigInteger('to_warehouse_id');
            $table->enum('status', ['pending', 'approved', 'in_transit', 'received', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->index('transfer_number');
            $table->index('status');
            $table->index(['from_warehouse_id', 'to_warehouse_id']);
        });

        Schema::create('inventory_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transfer_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('quantity');
            $table->integer('received_quantity')->default(0);
            $table->timestamps();

            $table->index('inventory_transfer_id');
            $table->index('product_variant_id');
        });

        // ==================== ACTIVITY LOGS ====================

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('action', 100);
            $table->string('entity_type', 100)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('additional_data')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
            $table->index('admin_id');
            $table->index('customer_id');
            $table->index('action');
        });

        // ==================== CRM & CONTENT ====================

        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('title', 200)->nullable();
            $table->longText('content');
            $table->enum('type', ['modal', 'slide_in', 'banner', 'fullscreen']);
            $table->enum('trigger', ['time_delay', 'exit_intent', 'scroll', 'immediate', 'custom']);
            $table->integer('delay_seconds')->default(5);
            $table->json('display_rules')->nullable();
            $table->json('targeting_rules')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('impressions')->default(0);
            $table->integer('conversions')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index(['starts_at', 'ends_at']);
            $table->index('type');
        });

        Schema::create('popup_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('popup_id');
            $table->string('session_id', 100)->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('action', ['impression', 'close', 'click', 'conversion']);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('page_data')->nullable();
            $table->timestamps();

            $table->index('popup_id');
            $table->index(['popup_id', 'action']);
            $table->index('customer_id');
        });

        Schema::create('home_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('title', 200)->nullable();
            $table->text('content')->nullable();
            $table->enum('type', ['slider', 'banner', 'products', 'categories', 'html', 'video', 'testimonials']);
            $table->json('data')->nullable();
            $table->json('display_rules')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('status');
            $table->index('sort_order');
            $table->index('type');
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name', 100);
            $table->string('author_designation', 100)->nullable();
            $table->unsignedBigInteger('author_image_id')->nullable();
            $table->integer('rating')->default(5);
            $table->text('content');
            $table->json('metadata')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('sort_order');
            $table->index('rating');
        });

        // ==================== REVIEWS & RATINGS ====================

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->string('title', 200)->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_admin_review')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'status']);
            $table->index('customer_id');
            $table->index('admin_id');
            $table->index('rating');
            $table->index('is_verified');
            $table->index('is_featured');
            $table->unique(['product_id', 'customer_id', 'order_item_id']);
        });

        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_review_id');
            $table->unsignedBigInteger('media_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_review_id', 'media_id']);
            $table->index('product_review_id');
        });

        Schema::create('review_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_review_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->enum('vote', ['helpful', 'not_helpful']);
            $table->timestamps();

            $table->unique(['product_review_id', 'customer_id', 'session_id']);
            $table->index('product_review_id');
        });

        // ==================== NOTIFICATIONS ====================

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('subject', 200);
            $table->text('content');
            $table->enum('type', ['email', 'sms', 'push', 'in_app']);
            $table->enum('trigger_event', [
                'order_placed',
                'order_confirmed',
                'order_shipped',
                'order_delivered',
                'order_cancelled',
                'payment_received',
                'payment_failed',
                'account_created',
                'password_reset',
                'abandoned_cart',
                'low_stock',
                'back_in_stock',
                'review_reminder'
            ]);
            $table->tinyInteger('is_active')->default(1);
            $table->json('variables')->nullable();
            $table->timestamps();

            $table->index('trigger_event');
            $table->index('type');
            $table->index('is_active');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('subject', 200);
            $table->text('content');
            $table->enum('type', ['email', 'sms', 'push', 'in_app']);
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered', 'read'])->default('pending');
            $table->json('data')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });

        // ==================== SEO & URL MANAGEMENT ====================

        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('robots', 50)->nullable();
            $table->json('og_tags')->nullable();
            $table->json('twitter_tags')->nullable();
            $table->json('structured_data')->nullable();
            $table->timestamps();

            $table->unique(['entity_type', 'entity_id']);
            $table->index(['entity_type', 'entity_id']);
        });

        Schema::create('url_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_url', 500);
            $table->string('target_url', 500);
            $table->enum('redirect_type', [301, 302, 307])->default(301);
            $table->tinyInteger('is_active')->default(1);
            $table->integer('hit_count')->default(0);
            $table->timestamps();

            $table->unique('source_url');
            $table->index('source_url');
            $table->index('is_active');
        });

        // ==================== GIFT CARDS ====================

        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->decimal('initial_value', 12, 2);
            $table->decimal('current_value', 12, 2);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('purchased_by')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('recipient_email', 150)->nullable();
            $table->string('recipient_name', 100)->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['active', 'used', 'expired', 'cancelled'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('status');
            $table->index('expires_at');
            $table->index('purchased_by');
            $table->index('recipient_id');
        });

        Schema::create('gift_card_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gift_card_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('gift_card_id');
            $table->index(['reference_type', 'reference_id']);
        });

        // ==================== COMMUNICATION LOGS ====================

        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('message_id', 100)->nullable();
            $table->string('from', 150);
            $table->string('to', 150);
            $table->string('subject', 200);
            $table->enum('status', ['sent', 'delivered', 'opened', 'clicked', 'bounced', 'failed'])->default('sent');
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();

            $table->index('to');
            $table->index('status');
            $table->index('sent_at');
        });

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('message_id', 100)->nullable();
            $table->string('from', 20);
            $table->string('to', 20);
            $table->text('message');
            $table->enum('status', ['sent', 'delivered', 'failed'])->default('sent');
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('to');
            $table->index('status');
            $table->index('sent_at');
        });

        // ==================== SETTINGS ====================

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general');
            $table->string('key', 100);
            $table->text('value')->nullable();
            $table->enum('type', ['text', 'textarea', 'number', 'boolean', 'select', 'multiselect', 'json', 'file'])->default('text');
            $table->text('options')->nullable();
            $table->string('label', 100);
            $table->text('description')->nullable();
            $table->tinyInteger('is_encrypted')->default(0);
            $table->tinyInteger('is_public')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['group', 'key']);
            $table->index('group');
            $table->index('sort_order');
        });

        // ==================== AUDIT TRAILS ====================

        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('event', 100);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('event');
            $table->index('created_at');
            $table->index('admin_id');
            $table->index('customer_id');
        });

        // ==================== ADD FOREIGN KEY CONSTRAINTS ====================

        // Customer segments
        Schema::table('customer_segment_members', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('customer_segment_id')->references('id')->on('customer_segments')->onDelete('cascade');
        });

        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('media')->onDelete('set null');
        });

        Schema::table('category_hierarchies', function (Blueprint $table) {
            $table->foreign('ancestor_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('descendant_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Brands
        Schema::table('brands', function (Blueprint $table) {
            $table->foreign('logo_id')->references('id')->on('media')->onDelete('set null');
        });

        // Attributes
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('media')->onDelete('set null');
        });

        Schema::table('category_attributes', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });

        // Specifications
        Schema::table('specification_values', function (Blueprint $table) {
            $table->foreign('specification_id')->references('id')->on('specifications')->onDelete('cascade');
        });

        Schema::table('spec_group_specs', function (Blueprint $table) {
            $table->foreign('spec_group_id')->references('id')->on('specification_groups')->onDelete('cascade');
            $table->foreign('specification_id')->references('id')->on('specifications')->onDelete('cascade');
        });

        Schema::table('category_spec_groups', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('spec_group_id')->references('id')->on('specification_groups')->onDelete('cascade');
        });

        // Tax system
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->foreign('tax_class_id')->references('id')->on('tax_classes')->onDelete('cascade');
        });

        // Shipping
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->foreign('shipping_zone_id')->references('id')->on('shipping_zones')->onDelete('cascade');
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
        });

        // Products
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('main_category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('tax_class_id')->references('id')->on('tax_classes')->onDelete('set null');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('variant_attributes', function (Blueprint $table) {
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');
        });

        Schema::table('product_specifications', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('specification_id')->references('id')->on('specifications')->onDelete('cascade');
            $table->foreign('specification_value_id')->references('id')->on('specification_values')->onDelete('cascade');
        });

        Schema::table('variant_images', function (Blueprint $table) {
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });

        // Product relationships
        Schema::table('category_product', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::table('product_tags', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::table('related_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('related_product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('cross_sell_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('cross_sell_product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('upsell_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('upsell_product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Price management
        Schema::table('price_histories', function (Blueprint $table) {
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('admins')->onDelete('set null');
        });

        Schema::table('tier_prices', function (Blueprint $table) {
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('customer_segment_id')->references('id')->on('customer_segments')->onDelete('cascade');
        });

        // Customer addresses
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        // Loyalty
        Schema::table('customer_loyalty', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('loyalty_program_id')->references('id')->on('loyalty_programs')->onDelete('cascade');
        });

        Schema::table('loyalty_transactions', function (Blueprint $table) {
            $table->foreign('customer_loyalty_id')->references('id')->on('customer_loyalty')->onDelete('cascade');
        });

        // Wishlists
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->foreign('wishlist_id')->references('id')->on('wishlists')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });

        // Offers
        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('customer_segment_id')->references('id')->on('customer_segments')->onDelete('set null');
        });

        Schema::table('offer_usages', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });

        Schema::table('offer_variants', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });

        Schema::table('offer_categories', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::table('offer_rewards', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('reward_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('reward_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });

        // Cart
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
            $table->foreign('shipping_address_id')->references('id')->on('customer_addresses')->onDelete('set null');
            $table->foreign('billing_address_id')->references('id')->on('customer_addresses')->onDelete('set null');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
        });

        // Orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('set null');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
        });

        Schema::table('order_status_history', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });

        // Payments
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
        });

        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
        });

        // Shipments
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        Schema::table('shipment_items', function (Blueprint $table) {
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
        });

        // Returns
        Schema::table('returns', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('refund_payment_id')->references('id')->on('payments')->onDelete('set null');
        });

        Schema::table('return_items', function (Blueprint $table) {
            $table->foreign('return_id')->references('id')->on('returns')->onDelete('cascade');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
        });

        // Stock management
        Schema::table('stock_history', function (Blueprint $table) {
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });

        Schema::table('warehouse_stocks', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });

        Schema::table('inventory_transfers', function (Blueprint $table) {
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
        });

        Schema::table('inventory_transfer_items', function (Blueprint $table) {
            $table->foreign('inventory_transfer_id')->references('id')->on('inventory_transfers')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });

        // Activity logs
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });

        // Popups
        Schema::table('popup_stats', function (Blueprint $table) {
            $table->foreign('popup_id')->references('id')->on('popups')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });

        // Testimonials
        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreign('author_image_id')->references('id')->on('media')->onDelete('set null');
        });

        // Reviews
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('set null');
        });

        Schema::table('review_images', function (Blueprint $table) {
            $table->foreign('product_review_id')->references('id')->on('product_reviews')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });

        Schema::table('review_votes', function (Blueprint $table) {
            $table->foreign('product_review_id')->references('id')->on('product_reviews')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });

        // Notifications
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('template_id')->references('id')->on('notification_templates')->onDelete('set null');
        });

        // Gift cards
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
            $table->foreign('purchased_by')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('customers')->onDelete('set null');
        });

        Schema::table('gift_card_transactions', function (Blueprint $table) {
            $table->foreign('gift_card_id')->references('id')->on('gift_cards')->onDelete('cascade');
        });

        // Audit trails
        Schema::table('audit_trails', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('audit_trails');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('gift_card_transactions');
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('url_redirects');
        Schema::dropIfExists('seo_metadata');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('review_votes');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('home_page_sections');
        Schema::dropIfExists('popup_stats');
        Schema::dropIfExists('popups');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('inventory_transfer_items');
        Schema::dropIfExists('inventory_transfers');
        Schema::dropIfExists('warehouse_stocks');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('stock_history');
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('shipment_items');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('payment_attempts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_sequences');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('offer_rewards');
        Schema::dropIfExists('offer_categories');
        Schema::dropIfExists('offer_variants');
        Schema::dropIfExists('offer_usages');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('customer_loyalty');
        Schema::dropIfExists('loyalty_programs');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('tier_prices');
        Schema::dropIfExists('price_histories');
        Schema::dropIfExists('upsell_products');
        Schema::dropIfExists('cross_sell_products');
        Schema::dropIfExists('related_products');
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('variant_images');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('variant_attributes');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('shipping_charges');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('tax_classes');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('category_spec_groups');
        Schema::dropIfExists('spec_group_specs');
        Schema::dropIfExists('specification_values');
        Schema::dropIfExists('specifications');
        Schema::dropIfExists('specification_groups');
        Schema::dropIfExists('category_attributes');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('category_hierarchies');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customer_segment_members');
        Schema::dropIfExists('customer_segments');
        Schema::dropIfExists('password_histories');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('media');
    }
};
