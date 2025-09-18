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
        Schema::table('blogs', function (Blueprint $table) {
            // Yapay zeka ile oluşturulan içerikler için yeni sütunlar ekliyoruz.
            // Bu sütunları 'content' sütunundan sonra eklemek, veritabanı yapısını düzenli tutar.
            $table->text('prompt')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Migration geri alındığında bu sütunu kaldır.
            $table->dropColumn('prompt');
        });
    }
};
