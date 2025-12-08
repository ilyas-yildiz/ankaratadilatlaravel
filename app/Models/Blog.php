<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder; // Scope için eklendi

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_url',
        'status',
        'published_at', // <--- YENİ EKLENDİ
        'category_id',
        'user_id',
        'order',
        'is_featured',
        'gallery_id',
        'author_id',
        'meta_description',
        'meta_keywords'
    ];
    
    protected $appends = ['image_full_url'];

    // YENİ EKLENDİ: Tarih formatını Carbon objesi olarak algılaması için
    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'boolean',
        'is_featured' => 'boolean',
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

    protected function imageFullUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/blog-images/128x128/' . $this->image_url) : null,
        );
    }
    
    // YENİ SCOPE: Frontend'de sadece "Blog::published()->get()" diyerek
    // hem tarihi gelmiş hem de statusu aktif olanları çekeceğiz.
    public function scopePublished(Builder $query): void
    {
        $query->where('status', true)
              ->whereNotNull('published_at')
              ->where('published_at', '<=', now());
    }

    // İlişkiler aynen kalıyor...
    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function gallery() { return $this->belongsTo(Gallery::class); }
    public function author(): BelongsTo { return $this->belongsTo(Author::class); }
}