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
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('link')->nullable();
            $table->string('button_text')->nullable();
            // Görseli kaydettiğimiz dosya adını tutacak alan
            $table->string('image_url')->nullable(); 
            // Sıralama alanı (SortableJS için)
            $table->unsignedInteger('order')->default(0)->index(); 
            // Durum alanı (Status Switch için)
            $table->boolean('status')->default(true); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};