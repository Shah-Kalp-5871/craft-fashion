<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $col) {
            $col->id();
            $col->string('title');
            $col->string('subtitle')->nullable();
            $col->enum('type', ['category', 'custom_products'])->default('category');
            $col->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $col->json('product_ids')->nullable(); // For custom products
            $col->string('style')->default('style_1'); // style_1 to style_5
            $col->integer('sort_order')->default(0);
            $col->boolean('status')->default(true);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
