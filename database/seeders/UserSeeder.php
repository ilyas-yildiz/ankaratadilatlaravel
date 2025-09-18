<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Emre Asan',
            'email' => 'emreasan@milhukuk.com',
            'password' => Hash::make('Emremil1?*'),
        ]);

        User::create([
            'name' => 'Bilgehan Utku',
            'email' => 'bilgehanutku@milhukuk.com',
            'password' => Hash::make('Bilgemil1?*'),
        ]);

        User::create([
            'name' => 'Enderun Digital',
            'email' => 'destek@enderundigital.com',
            'password' => Hash::make('Ender06un?*'),
        ]);
    }
}
