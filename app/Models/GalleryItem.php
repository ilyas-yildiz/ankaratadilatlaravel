<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model
{
    use HasFactory;

    /**
     * gallery_items tablosunun adı.
     *
     * @var string
     */
    protected $table = 'gallery_items';

    /**
     * Toplu atanabilir (mass assignable) alanlar.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gallery_id',
        'image',
        'order',
        'status',
    ];

    /**
     * Bir galeri öğesi (resim) tek bir galeriye aittir.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}
