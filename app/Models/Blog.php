<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute; // Attribute sınıfını dahil et
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_url',
        'status',
        'category_id',
        'user_id',
        'order',
        'is_featured',
        'gallery_id',
        'author_id',
        'meta_description',
        'meta_keywords'
    ];
    
    // YENİ EKLENDİ: Model JSON'a çevrildiğinde bu 'sanal' alanı da ekle.
    protected $appends = ['image_full_url'];

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

    // YENİ EKLENDİ: 'image_full_url' adında sanal bir attribute oluşturuyoruz.
    /**
     * image_url'den tam erişilebilir bir URL oluşturur.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function imageFullUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/blog-images/128x128/' . $this->image_url) : null,
        );
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}