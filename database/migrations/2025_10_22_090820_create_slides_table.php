<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0); // Sıralama
            $table->string('title'); // Başlık
            $table->string('subtitle')->nullable(); // Alt Başlık (isteğe bağlı)
            $table->string('link')->nullable(); // Buton Linki (isteğe bağlı)
            $table->string('button_text')->nullable(); // Buton Metni (isteğe bağlı)
            $table->string('image_url'); // Görsel (zorunlu varsayalım)
            $table->boolean('status')->default(true); // Durum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};