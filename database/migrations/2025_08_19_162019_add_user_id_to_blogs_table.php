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
            // "users" tablosuna bir foreign key (dış anahtar) olarak `user_id` sütununu ekler.
            // Bu, her blog yazısının bir kullanıcıya ait olduğunu belirler.
            $table->foreignId('user_id')
                ->nullable() // `user_id` boş bırakılabilir (null)
                ->constrained() // users tablosuyla ilişkilendirilir
                ->onDelete('set null'); // Bir kullanıcı silindiğinde, ilgili blogların `user_id` değeri null olur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Eğer sütun varsa, önce foreign key'i sonra sütunu siler.
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
