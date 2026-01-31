<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_is_block_to_customers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_block')->default(false)->after('status');
            $table->timestamp('blocked_at')->nullable()->after('is_block');
            $table->string('block_reason')->nullable()->after('blocked_at');
            $table->foreignId('blocked_by')->nullable()->constrained('admins')->onDelete('set null')->after('block_reason');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_block', 'blocked_at', 'block_reason', 'blocked_by']);
        });
    }
};
