<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Veritabanı kayıtlarını çalıştır.
     */
    public function run(): void
    {
        // Örnek kategoriler ve galeriler olduğunu varsayarak rastgele ID'ler alalım.
        // Eğer kategoriler ve galeriler tablolarınız boşsa, bu kısmı düzenlemeniz gerekebilir.
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $galleryIds = DB::table('galleries')->pluck('id')->toArray();

        // Eğer kategori veya galeri tablosu boşsa, geçici olarak rastgele sayılar kullanalım.
        if (empty($categoryIds)) {
            $categoryIds = [1, 2, 3];
        }
        if (empty($galleryIds)) {
            $galleryIds = [1, 2, 3];
        }

        $blogs = [
            [
                'title' => 'Laravel\'de Temel CRUD İşlemleri',
                'slug' => Str::slug('Laravel\'de Temel CRUD İşlemleri'),
                'content' => 'Bu makale, Laravel framework\'ünde Temel CRUD (Create, Read, Update, Delete) işlemlerini nasıl gerçekleştireceğinizi anlatmaktadır. Model, Controller ve View bileşenlerinin nasıl birlikte çalıştığını keşfedin.',
                'image_url' => 'https://via.placeholder.com/600x400.png?text=Laravel+CRUD',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'gallery_id' => $galleryIds[array_rand($galleryIds)],
                'is_featured' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Web Geliştiriciler İçin CSS İpuçları',
                'slug' => Str::slug('Web Geliştiriciler İçin CSS İpuçları'),
                'content' => 'Daha temiz ve etkili CSS yazmanıza yardımcı olacak on ipucunu bir araya getirdik. Flexbox, Grid ve değişkenler gibi modern CSS özelliklerini nasıl kullanacağınızı öğrenin.',
                'image_url' => 'https://via.placeholder.com/600x400.png?text=CSS+Tips',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'gallery_id' => $galleryIds[array_rand($galleryIds)],
                'is_featured' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'JavaScript ile Dinamik İçerik Oluşturma',
                'slug' => Str::slug('JavaScript ile Dinamik İçerik Oluşturma'),
                'content' => 'Sayfalarınıza etkileşim katmanın en iyi yolu JavaScript kullanmaktır. Bu blog yazısında, DOM manipülasyonu ve olay dinleyicileri (event listeners) kullanarak dinamik içerik oluşturmanın temellerini ele alıyoruz.',
                'image_url' => 'https://via.placeholder.com/600x400.png?text=JavaScript',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'gallery_id' => $galleryIds[array_rand($galleryIds)],
                'is_featured' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Veritabanı Tasarımının Püf Noktaları',
                'slug' => Str::slug('Veritabanı Tasarımının Püf Noktaları'),
                'content' => 'İyi bir veritabanı tasarımı, uygulamanızın performansını ve ölçeklenebilirliğini doğrudan etkiler. Bu yazıda, doğru tabloları ve ilişkileri oluşturmanın önemini inceliyoruz.',
                'image_url' => 'https://via.placeholder.com/600x400.png?text=Database+Design',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'gallery_id' => $galleryIds[array_rand($galleryIds)],
                'is_featured' => false,
                'status' => false, // Taslak blog
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Web Güvenliğinde Dikkat Edilmesi Gerekenler',
                'slug' => Str::slug('Web Güvenliğinde Dikkat Edilmesi Gerekenler'),
                'content' => 'Siber saldırıların arttığı günümüzde, web uygulamalarının güvenliği her zamankinden daha önemli. SQL Injection, XSS ve CSRF gibi yaygın güvenlik açıklarını nasıl önleyeceğinizi öğrenin.',
                'image_url' => 'https://via.placeholder.com/600x400.png?text=Web+Security',
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'gallery_id' => $galleryIds[array_rand($galleryIds)],
                'is_featured' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('blogs')->insert($blogs);
    }
}
