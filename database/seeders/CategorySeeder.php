<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Web Geliştirme',
                'slug' => Str::slug('Web Geliştirme'),
                'type' => 'blog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mobil Uygulamalar',
                'slug' => Str::slug('Mobil Uygulamalar'),
                'type' => 'blog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tasarım',
                'slug' => Str::slug('Tasarım'),
                'type' => 'blog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laravel',
                'slug' => Str::slug('Laravel'),
                'type' => 'blog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
