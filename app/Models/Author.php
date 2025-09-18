<?php

namespace App\Models;

use App\Services\ImageService; // ImageService'i kullanabilmek için dahil ediyoruz
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // HasMany ilişkisini kullanacağımızı belirtiyoruz

class Author extends Model
{
    use HasFactory;

    /**
     * Toplu atama (mass assignment) için izin verilen alanlar.
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'img_url',
        'status',
        'order',
    ];

    /**
     * Model "booted" olduğunda çalışacak olan metot.
     * Bu metot, model olaylarını (events) dinlemek için en doğru yerdir.
     */
    protected static function booted(): void
    {
        static::deleting(function (Author $author) {
            if ($author->img_url) {
                $imageService = app(ImageService::class);
                $sizes = ['263x272', '100x100'];
                $imageService->deleteImages($author->img_url, 'authors', $sizes);
            }
        });
    }

    /**
     * Bu yazara ait olan blog yazılarını döndüren ilişki.
     * BİR YAZARIN BİRDEN ÇOK BLOG'U OLABİLİR (hasMany)
     * YENİ EKLENEN METOT
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs(): HasMany
    {
        // Bu, 'blogs' tablosundaki 'author_id' sütunu üzerinden
        // Blog modellerini bu yazara bağlar.
        return $this->hasMany(Blog::class);
    }
}

