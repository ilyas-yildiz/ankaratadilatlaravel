<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('galleries')->insert([
            [
                'name' => 'Web Geliştirme Galerisi',
                'slug' => 'web-gelistirme-galerisi',
            ],
            [
                'name' => 'Tasarım Çalışmaları',
                'slug' => 'tasarim-calismalari',
            ],
            [
                'name' => 'Programlama Sanatı',
                'slug' => 'programlama-sanati',
            ],
        ]);
    }
}
