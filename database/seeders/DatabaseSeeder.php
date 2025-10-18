<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Hash sınıfını kullanmak için ekleyelim

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Yalnızca admin@admin.com kullanıcısını oluşturalım
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(), // Opsiyonel, kullanıcıyı doğrulanmış olarak işaretler
            'password' => Hash::make('password'), // 'password' kelimesini güvenli bir şekilde hash'leyelim
        ]);
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            GallerySeeder::class,
            BlogSeeder::class,
            UpdateBlogsTableSeeder::class,
            SettingsTableSeeder::class,
        ]);


    }
}
