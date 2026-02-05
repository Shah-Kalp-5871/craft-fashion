<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->json('request_data')->nullable()->after('status');
            $table->json('response_data')->nullable()->after('request_data');
        });
    }

    public function down(): void
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->dropColumn(['request_data', 'response_data']);
        });
    }
};
