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
            $table->string('gateway', 50)->after('order_id')->nullable();
            $table->string('gateway_order_id', 100)->after('gateway')->nullable()->index();
            $table->string('gateway_payment_id', 100)->after('gateway_order_id')->nullable()->index();
            $table->string('currency', 3)->after('amount')->default('INR');
            
            // Make payment_method_id nullable as we might use gateway name instead
            $table->unsignedBigInteger('payment_method_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('payment_attempts', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'gateway_order_id', 'gateway_payment_id', 'currency']);
            $table->unsignedBigInteger('payment_method_id')->nullable(false)->change();
        });
    }
};
