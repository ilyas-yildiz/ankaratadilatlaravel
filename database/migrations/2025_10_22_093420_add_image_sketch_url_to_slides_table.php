<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            // image_url sütunundan sonra, null olabilen yeni görsel sütununu ekle
            $table->string('image_sketch_url')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropColumn('image_sketch_url');
        });
    }
};