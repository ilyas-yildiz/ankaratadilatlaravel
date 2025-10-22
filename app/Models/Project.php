<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Galeri ilişkisi için
use App\Services\ImageService; // Görsel silme için

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'title',
        'slug',
        'content',
        'image_url', // Ana görsel eklendi
        'start_date',
        'end_date',
        'client',
        'project_manager',
        'location',
        'project_type',
        'gallery_id',
        'status',
    ];

    // Tarih alanlarını Carbon nesnesi olarak kullanmak için
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Ana görselin tam URL'si için accessor
    protected $appends = ['image_full_url'];

    /**
     * image_url'den tam erişilebilir bir URL oluşturur.
     */
    protected function imageFullUrl(): Attribute
    {
        $imagePath = 'project-images'; // Projeler için klasör adı
        return Attribute::make(
            // Önizleme için 128x128 boyutunu varsayıyoruz
            get: fn () => $this->image_url ? asset('storage/' . $imagePath . '/128x128/' . $this->image_url) : null, 
        );
    }

    /**
     * Bu projenin ilişkili olduğu Galeriyi döndürür.
     */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    // Kayıt silindiğinde ilişkili ana görseli de silmek için
    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            if ($project->image_url) {
                $imageService = app(ImageService::class);
                $imagePath = 'project-images'; // Yukarıdaki path ile aynı olmalı
                // Proje için kullanılacak boyutları tanımla (Gerekiyorsa güncelle)
                $sizes = ['1920x1080', '600x400', '128x128']; 
                $imageService->deleteImages($project->image_url, $imagePath, $sizes);
            }
        });
    }
}