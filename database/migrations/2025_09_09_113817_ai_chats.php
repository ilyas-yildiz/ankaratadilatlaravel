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
        Schema::create('ai_chats', function (Blueprint $table) {
            $table->id();
            // Sohbeti hangi kullanıcının oluşturduğunu belirtir.
            // Kullanıcı silinirse, ona ait sohbetler de silinir.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Kullanıcının sohbete verdiği başlık.
            $table->string('title');
            // Tüm sohbet geçmişini JSON formatında saklar.
            $table->json('history');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chats');
    }
};
