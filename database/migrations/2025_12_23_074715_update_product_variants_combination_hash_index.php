<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            /*
             |--------------------------------------------------------------------------
             | Remove WRONG unique index (global uniqueness)
             |--------------------------------------------------------------------------
             */
            $table->dropUnique('product_variants_combination_hash_unique');

            /*
             |--------------------------------------------------------------------------
             | Add CORRECT composite unique index
             | One combination per product
             |--------------------------------------------------------------------------
             */
            $table->unique(
                ['product_id', 'combination_hash'],
                'product_variant_unique_combination'
            );
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            /*
             |--------------------------------------------------------------------------
             | Remove composite index
             |--------------------------------------------------------------------------
             */
            $table->dropUnique('product_variant_unique_combination');

            /*
             |--------------------------------------------------------------------------
             | Restore old (incorrect) index (rollback safety)
             |--------------------------------------------------------------------------
             */
            $table->unique(
                'combination_hash',
                'product_variants_combination_hash_unique'
            );
        });
    }
};
