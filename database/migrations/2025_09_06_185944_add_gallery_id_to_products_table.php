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
        Schema::table('products', function (Blueprint $table) {
            // gallery_id sütununu ekliyoruz. nullable() olması önemli.
            // onDelete('set null') sayesinde, bir galeri silinirse ilgili ürünler silinmez,
            // sadece gallery_id alanları null olarak güncellenir.
            $table->foreignId('gallery_id')
                ->nullable()
                ->after('image_url') // image_url sütunundan sonra gelsin
                ->constrained('galleries')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Geri alma işlemi için önce foreign key kısıtlamasını kaldırıyoruz.
            $table->dropForeign(['gallery_id']);
            // Sonra sütunu siliyoruz.
            $table->dropColumn('gallery_id');
        });
    }
};
