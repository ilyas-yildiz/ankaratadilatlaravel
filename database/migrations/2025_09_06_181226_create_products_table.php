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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ürün adı
            $table->string('slug')->unique(); // SEO dostu URL için

            // Fiyatlandırma için en doğru veri tipi `decimal`'dır.
            // 8 basamak toplam, 2'si ondalık (örn: 999999.99)
            $table->decimal('price', 8, 2)->default(0.00);

            // Stok Kodu (Stock Keeping Unit)
            $table->string('sku')->unique()->nullable();

            // Uzun ürün açıklamaları için
            $table->longText('content')->nullable();

            // BaseResourceController ile uyumlu resim alanı
            $table->string('image_url')->nullable();

            // Sıralama ve durum bilgisi
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
