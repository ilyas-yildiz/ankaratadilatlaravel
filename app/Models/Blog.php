<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // BelongsTo ilişkisini kullanacağımızı belirtiyoruz


class Blog extends Model
{
    use HasFactory;

    /**
     * Laravel'in kitlesel atama (mass assignment) korumasını aşmak için
     * eklenebilir (fillable) sütunlar. Bu sütunlara toplu veri girişi yapılabilir.
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_url', // 'image' yerine doğru sütun adı
        'status',
        'category_id',
        'user_id',
        'order',
        'is_featured',
        'gallery_id',
        'is_featured',
        'author_id',
        'meta_description',
        'meta_keywords'
    ];

    protected static function booted(): void
    {
        static::deleting(function (Blog $blog) {
            if ($blog->image_url) {
                $imageService = app(ImageService::class);
                $sizes = ['1124x790', '562x395', '274x183', '128x128'];
                $imageService->deleteImages($blog->image_url, 'blog-images', $sizes);
            }
        });
    }

    /**
     * Blog yazısının ait olduğu kullanıcıyı temsil eden ilişki.
     * Bir blog yazısı, tek bir kullanıcıya aittir (BelongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Blog yazısının ait olduğu kategoriyi temsil eden ilişki.
     * Bir blog yazısı, tek bir kategoriye aittir (BelongsTo).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Blog yazısının ait olduğu galeriye temsil eden ilişki.
     * Bir blog yazısı, tek bir galeriye aittir (BelongsTo).
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Bu blog yazısının ait olduğu yazarı döndüren ilişki.
     * BİR BLOG BİR YAZARA AİTTİR (belongsTo)
     * YENİ EKLENEN METOT
     */
    public function author(): BelongsTo
    {
        // Bu, 'blogs' tablosundaki 'author_id' sütunu üzerinden
        // Author modelini bu blog yazısına bağlar.
        return $this->belongsTo(Author::class);
    }
}
