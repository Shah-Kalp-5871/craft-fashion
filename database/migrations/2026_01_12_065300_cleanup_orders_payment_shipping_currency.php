<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // ADD ENUM COLUMNS
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['cod', 'online'])
                      ->default('online')
                      ->after('shipping_method_id');
            }

            if (!Schema::hasColumn('orders', 'shipping_method')) {
                $table->enum('shipping_method', ['shiprocket', 'custom'])
                      ->default('shiprocket')
                      ->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'currency')) {
                $table->enum('currency', ['INR'])
                      ->default('INR')
                      ->after('shipping_method');
            }
        });

        // DROP FOREIGN KEYS SAFELY
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders', 'payment_method_id')) {
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            }

            if (Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->dropForeign(['shipping_method_id']);
                $table->dropColumn('shipping_method_id');
            }

            if (Schema::hasColumn('orders', 'currency_id')) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            }
        });
    }

    public function down(): void
    {
        // intentionally skipped
    }
};
