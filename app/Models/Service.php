<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService; // Görsel silme için dahil edelim

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'order', // Sıralama eklendi
        'title',
        'slug',
        'content', // Standart 'content' adı kullanıldı
        'image_url',
        'status',
    ];

    protected $appends = ['image_full_url'];

    /**
     * image_url'den tam erişilebilir bir URL oluşturur.
     */
    protected function imageFullUrl(): Attribute
    {
        $imagePath = 'service-images'; // Hizmetler için klasör adı
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/' . $imagePath . '/128x128/' . $this->image_url) : null,
        );
    }

    // Kayıt silindiğinde ilişkili görselleri de silmek için (Blog modelindeki gibi)
    protected static function booted(): void
    {
        static::deleting(function (Service $service) {
            if ($service->image_url) {
                $imageService = app(ImageService::class);
                $imagePath = 'service-images'; // Yukarıdaki path ile aynı olmalı
                // Service için kullanılacak boyutları tanımla
                $sizes = ['1024x768', '512x384', '128x128']; 
                $imageService->deleteImages($service->image_url, $imagePath, $sizes);
            }
        });
    }
}