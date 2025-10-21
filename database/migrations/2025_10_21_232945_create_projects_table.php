<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(0); // Sıralama
            $table->string('title'); // Başlık
            $table->longText('content'); // Açıklama (Summernote için)
            $table->string('image_url')->nullable(); // Ana Proje Görseli (Hizmetlerdeki gibi)
            $table->date('start_date')->nullable(); // Başlangıç Tarihi
            $table->date('end_date')->nullable(); // Bitiş Tarihi
            $table->string('client'); // Müşteri
            $table->string('project_manager'); // Proje Yöneticisi
            $table->string('location')->nullable(); // Lokasyon
            $table->string('project_type')->nullable(); // Proje Tipi

            // Galeri ilişkisi (galleries tablosuna bağlanır)
            // onDelete('set null') -> Galeri silinirse bu projede gallery_id null olur.
            $table->foreignId('gallery_id')->nullable()->constrained('galleries')->onDelete('set null'); 

            $table->boolean('status')->default(true); // Durum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};