<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File; // << BU SATIRI EKLE

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'link',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Slide $slide) {
            if ($slide->image_url) {
                // Controller'da tanımladığımız boyutları ve yolu burada da kullanıyoruz.
                $imageService = app(ImageService::class);
                $sizes = ['1920x1080', '128x128'];
                $path = 'slide-images';
                $imageService->deleteImages($slide->image_url, $path, $sizes);
            }
        });
    }


}
