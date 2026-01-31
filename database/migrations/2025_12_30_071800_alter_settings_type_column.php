<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Change enum to string
            $table->string('type', 50)->default('text')->change();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Revert (only if needed)
            $table->enum('type', [
                'text',
                'textarea',
                'number',
                'boolean',
                'select',
                'multiselect',
                'json',
                'file'
            ])->default('text')->change();
        });
    }
};
