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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway_order_id', 100)->nullable()->after('transaction_id')->index();
            $table->json('payment_details')->nullable()->after('gateway_order_id');
            $table->string('currency', 3)->default('INR')->after('amount');
            $table->json('gateway_response')->nullable()->after('payment_details');
            
            // Modify payment_method to be string instead of enum to accept 'razorpay' etc
            $table->string('payment_method', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['gateway_order_id', 'payment_details', 'currency', 'gateway_response']);
            // We can't easily revert payment_method string to enum without potential data loss or strict SQL modes
        });
    }
};
