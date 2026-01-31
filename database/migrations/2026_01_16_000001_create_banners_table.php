<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $col) {
            $col->id();
            $col->string('title')->nullable();
            $col->string('subtitle')->nullable();
            $col->string('image');
            $col->string('cta_text')->nullable();
            $col->string('cta_link')->nullable();
            $col->integer('sort_order')->default(0);
            $col->boolean('status')->default(true);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
