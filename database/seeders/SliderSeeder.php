<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slide::create([
            'order' => 1,
            'title' => 'Ahşap & Laminat Döşeme',
            'subtitle' => 'Modern ve Şık Tasarımlar',
            'link' => '#',
            'button_text' => 'Detaylı Bilgi',
            'image_url' => 'assets/images/main-slider/slider5/slide1.jpg',
            'image_sketch_url' => 'assets/images/main-slider/slider5/slide1-sk.jpg',
            'status' => true,
        ]);

        Slide::create([
            'order' => 2,
            'title' => 'Profesyonel Boya Badana',
            'subtitle' => 'Evinize Renk Katın',
            'link' => '#',
            'button_text' => 'İletişime Geçin',
            'image_url' => 'assets/images/main-slider/slider5/slide2.jpg',
            'image_sketch_url' => 'assets/images/main-slider/slider5/slide2-sk.jpg',
            'status' => true,
        ]);
    }
}
