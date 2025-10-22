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
    Schema::table('projects', function (Blueprint $table) {
        // title sütunundan sonra, null olabilen ve unique (benzersiz) slug ekle
        $table->string('slug')->nullable()->unique()->after('title'); 
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn('slug');
    });
}
};
