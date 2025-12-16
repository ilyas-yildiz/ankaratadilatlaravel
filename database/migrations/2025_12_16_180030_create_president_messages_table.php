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
        Schema::create('president_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Başlık
            $table->text('short_content')->nullable(); // Kısa içerik
            $table->longText('content'); // Uzun içerik
            $table->string('image_url')->nullable(); // Görsel
            $table->boolean('status')->default(true); // Durum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('president_messages');
    }
};
