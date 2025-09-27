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
            $table->id(); // Otomatik artan ID (Primary Key)
            $table->string('title'); // Slayt başlığı için metin sütunu
            $table->string('subtitle')->nullable(); // Alt başlık, ->nullable() sayesinde boş bırakılabilir
            $table->string('image_url'); // Görselin dosya yolu için metin sütunu
            $table->string('link')->nullable(); // Link için metin sütunu, boş bırakılabilir
            $table->integer('order')->default(0); // Sıralama için sayı sütunu, varsayılan değeri 0
            $table->boolean('status')->default(true); // Durum için (aktif/pasif), varsayılan değeri true (aktif)
            $table->timestamps(); // `created_at` ve `updated_at` sütunlarını otomatik oluşturur
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
