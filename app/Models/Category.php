<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // HasMany ilişkisini kullanacağımızı belirtiyoruz

class Category extends Model
{
    use HasFactory;

    // Mass assignment için izin verilen alanlar
    protected $fillable = ['name', 'status', 'order', 'slug', 'type'];

    /**
     * Bu kategoriye ait olan blog yazılarını döndüren ilişki.
     * BİR KATEGORİNİN BİRDEN ÇOK BLOG'U OLABİLİR (hasMany)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs(): HasMany
    {
        // Bu, 'blogs' tablosundaki 'category_id' sütunu üzerinden
        // Blog modellerini bu kategoriye bağlar.
        return $this->hasMany(Blog::class);
    }
}
