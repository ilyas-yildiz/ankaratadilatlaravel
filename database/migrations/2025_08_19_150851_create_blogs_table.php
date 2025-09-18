<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration'ı çalıştır.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('image_url')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('gallery_id')->nullable()->constrained('galleries')->onDelete('set null');
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true); // 1 = yayınlandı, 0 = taslak
            $table->timestamps();
        });
    }

    /**
     * Migration'ı geri al (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
