<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0); // Sıralama için
            $table->string('title'); // Başlık
            $table->longText('content'); // İçerik (Summernote için)
            $table->string('image_url')->nullable(); // Görsel (isteğe bağlı)
            $table->boolean('status')->default(true); // Durum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};