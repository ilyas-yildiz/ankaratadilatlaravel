<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Eğer görsel sileceksen ImageService'i dahil et
use App\Services\ImageService; 

class About extends Model
{
    use HasFactory;

    // Formdan toplu veri alırken izin verilen alanlar
    protected $fillable = [
        'title',
        'short_content',
        'content',
        'image_url',
        'status',
    ];

    // Görselin tam URL'sini oluşturmak için (Blog modelindeki gibi)
    protected $appends = ['image_full_url'];

    /**
     * image_url'den tam erişilebilir bir URL oluşturur.
     * Hakkımızda görselinin blog görsellerinden farklı bir klasörde olduğunu varsayalım.
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function imageFullUrl(): Attribute
    {
        // Görsel yolu farklıysa burayı güncelle ('about-images')
        $imagePath = 'about-images'; 
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/' . $imagePath . '/128x128/' . $this->image_url) : null,
        );
    }

    // Eğer About kaydı silindiğinde görseli de silmek istersen:
    
    protected static function booted(): void
    {
        static::deleting(function (About $about) {
            if ($about->image_url) {
                $imageService = app(ImageService::class);
                $imagePath = 'about-images'; // Görsel yoluyla eşleşmeli
                $sizes = ['large', 'medium', 'thumbnail', '128x128']; // Kullandığın boyutlar
                $imageService->deleteImages($about->image_url, $imagePath, $sizes);
            }
        });
    }
    
}