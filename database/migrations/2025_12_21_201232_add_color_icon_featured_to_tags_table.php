<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('slug');
            $table->string('icon', 50)->nullable()->after('color');
            $table->tinyInteger('featured')->default(0)->after('status');
            $table->string('description', 500)->nullable()->after('name');

            $table->index('featured');
            $table->index('color');
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['color', 'icon', 'featured', 'description']);
            $table->dropIndex(['featured', 'color']);
        });
    }
};
