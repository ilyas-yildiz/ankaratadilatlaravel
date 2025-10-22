<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService; // Görsel silme için

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'title',
        'subtitle',
        'link',
        'button_text',
        'image_url',
        'image_sketch_url',
        'status',
    ];

    // Görselin tam URL'si için accessor
    protected $appends = ['image_full_url', 'image_sketch_full_url'];
    /**
     * image_url'den tam erişilebilir bir URL oluşturur.
     */
    protected function imageFullUrl(): Attribute
    {
        $imagePath = 'slide-images'; // Slider görselleri için klasör adı
        return Attribute::make(
            // Önizleme için 128x128 boyutunu varsayıyoruz
            get: fn() => $this->image_url ? asset('storage/' . $imagePath . '/128x128/' . $this->image_url) : null,
        );
    }

    protected function imageSketchFullUrl(): Attribute
    {
        $imagePath = 'slide-images-sketch'; // Sketch görselleri için FARKLI bir klasör adı kullanalım
        return Attribute::make(
            get: fn() => $this->image_sketch_url ? asset('storage/' . $imagePath . '/128x128/' . $this->image_sketch_url) : null,
        );
    }

    // Kayıt silindiğinde ilişkili görselleri de silmek için
    protected static function booted(): void
    {
        static::deleting(function (Slide $slide) {
            $imageService = app(ImageService::class);
            $sizes = ['1920x1080', '1280x720', '128x128']; // Boyutlar aynı kalabilir

            // Ana görseli sil
            if ($slide->image_url) {
                $imagePath = 'slide-images';
                $imageService->deleteImages($slide->image_url, $imagePath, $sizes);
            }
            // Sketch görselini sil
            if ($slide->image_sketch_url) {
                $imagePathSketch = 'slide-images-sketch'; // Farklı klasör adı
                // Sketch için farklı boyutlar varsa burayı güncelle
                $sketchSizes = $sizes; // Şimdilik aynı boyutları varsayalım
                $imageService->deleteImages($slide->image_sketch_url, $imagePathSketch, $sketchSizes);
            }
        });
    }
}