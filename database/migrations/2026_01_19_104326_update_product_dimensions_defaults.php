<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing NULL or 0 records FIRST to avoid data truncation
        DB::table('product_variants')
            ->whereNull('weight')
            ->orWhere('weight', 0)
            ->update(['weight' => 0.100]);

        DB::table('product_variants')
            ->whereNull('length')
            ->orWhere('length', 0)
            ->update(['length' => 10.00]);

        DB::table('product_variants')
            ->whereNull('width')
            ->orWhere('width', 0)
            ->update(['width' => 10.00]);

        DB::table('product_variants')
            ->whereNull('height')
            ->orWhere('height', 0)
            ->update(['height' => 10.00]);

        // Products
        DB::table('products')
            ->whereNull('weight')
            ->orWhere('weight', 0)
            ->update(['weight' => 0.100]);

        DB::table('products')
            ->whereNull('length')
            ->orWhere('length', 0)
            ->update(['length' => 10.00]);

        DB::table('products')
            ->whereNull('width')
            ->orWhere('width', 0)
            ->update(['width' => 10.00]);

        DB::table('products')
            ->whereNull('height')
            ->orWhere('height', 0)
            ->update(['height' => 10.00]);

        // Now update table schema defaults
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->default(0.100)->change();
            $table->decimal('length', 8, 2)->default(10.00)->change();
            $table->decimal('width', 8, 2)->default(10.00)->change();
            $table->decimal('height', 8, 2)->default(10.00)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->default(0.100)->change();
            $table->decimal('length', 8, 2)->default(10.00)->change();
            $table->decimal('width', 8, 2)->default(10.00)->change();
            $table->decimal('height', 8, 2)->default(10.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->nullable()->default(null)->change();
            $table->decimal('length', 8, 2)->nullable()->default(null)->change();
            $table->decimal('width', 8, 2)->nullable()->default(null)->change();
            $table->decimal('height', 8, 2)->nullable()->default(null)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->nullable()->default(null)->change();
            $table->decimal('length', 8, 2)->nullable()->default(null)->change();
            $table->decimal('width', 8, 2)->nullable()->default(null)->change();
            $table->decimal('height', 8, 2)->nullable()->default(null)->change();
        });
    }
};
