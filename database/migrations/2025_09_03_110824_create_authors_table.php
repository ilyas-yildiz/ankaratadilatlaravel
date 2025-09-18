<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 'authors' adında yeni bir tablo oluşturuyoruz.
        Schema::create('authors', function (Blueprint $table) {
            // Otomatik artan, primary key olan 'id' sütunu.
            $table->id();

            // Yazarın adı ve soyadı için 'title' adında bir string sütunu.
            $table->string('title');

            // Yazarın adı ve soyadı için 'slug' adında bir string sütunu.
            $table->string('slug')->unique();

            // Yazar hakkında bilgi için 'description' adında, uzun metinler alabilen bir TEXT sütunu. Boş olabilir.
            $table->text('description')->nullable();

            // Yazarın görselinin dosya yolu için 'img_url' adında bir string sütunu. Boş olabilir.
            $table->string('img_url')->nullable();

            // Yazarın durumunu (0: Pasif, 1: Aktif) belirtmek için 'isActive' adında boolean sütunu.
            // Varsayılan olarak 1 (Aktif) ayarlıyoruz.
            $table->boolean('status')->default(true);

            // Yazarları sıralamak için 'rank' adında bir integer sütunu.
            // Varsayılan olarak 0 ayarlıyoruz.
            $table->integer('order')->default(0);

            // 'created_at' ve 'updated_at' timestamp sütunları.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
};
