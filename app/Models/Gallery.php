<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    use HasFactory;

    /**
     * Toplu atanabilir (mass assignable) alanlar.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'cover_image',
        'order',
        'status',
    ];

    /**
     * Bir galerinin birden fazla öğesi (resmi) olabilir.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class);
    }
}
