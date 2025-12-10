<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Services\ImageService;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'type', 
        'status', 
        'order', 
        'parent_id',     // Yeni
        'image_url',     // Yeni
        'color',         // Yeni
        'description',   // Yeni
        'meta_title',    // Yeni
        'meta_description', // Yeni
        'meta_keywords'  // Yeni
    ];

    protected $appends = ['image_full_url'];

    // Görsel silme mantığı (Blog modelindeki gibi)
    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            if ($category->image_url) {
                $imageService = app(ImageService::class);
                // Kategori resimleri için boyutlar
                $sizes = ['800x600', '400x300', '100x100']; 
                $imageService->deleteImages($category->image_url, 'category-images', $sizes);
            }
        });
    }

    // İlişkiler
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    // Üst Kategori (Parent)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Alt Kategoriler (Children)
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Accessor: Tam resim yolu
    protected function imageFullUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/category-images/100x100/' . $this->image_url) : null,
        );
    }
}