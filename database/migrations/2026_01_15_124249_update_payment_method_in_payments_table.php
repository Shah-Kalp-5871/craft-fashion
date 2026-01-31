<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // add enum payment_method
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', ['cod', 'online'])
                      ->after('order_id');
            }

            // drop old foreign key + column
            if (Schema::hasColumn('payments', 'payment_method_id')) {
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            }
        });
    }

    public function down(): void
    {
        // optional rollback (skipped)
    }
};

