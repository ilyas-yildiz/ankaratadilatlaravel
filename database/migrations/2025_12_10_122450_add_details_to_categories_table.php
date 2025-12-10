<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Hiyerarşi için (Kendi kendine ilişki)
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            
            // Görsel ve Tasarım
            $table->string('image_url')->nullable()->after('slug');
            $table->string('color', 20)->default('#405189')->after('image_url'); // Varsayılan lacivert
            
            // İçerik ve SEO
            $table->text('description')->nullable()->after('name');
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();

            // İlişki tanımı (Ana kategori silinirse alt kategori boşa düşsün)
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'image_url', 'color', 'description', 'meta_title', 'meta_description', 'meta_keywords']);
        });
    }
};