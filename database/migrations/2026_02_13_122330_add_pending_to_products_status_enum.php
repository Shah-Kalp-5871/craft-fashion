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
        // Use raw SQL to update ENUM since Laravel Blueprint's change() 
        // has issues with ENUM in older DB versions, or often requires doctrine/dbal
        // and we want to be safe.
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('draft', 'pending', 'active', 'inactive', 'out_of_stock') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To reverse, we go back to the original list. 
        // Note: any records with 'pending' would be truncated/invalidated.
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('draft', 'active', 'inactive', 'out_of_stock') DEFAULT 'draft'");
    }
};
