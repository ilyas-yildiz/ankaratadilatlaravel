<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService;

class PresidentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_content',
        'content',
        'image_url',
        'status',
    ];

    protected $appends = ['image_full_url'];

    /**
     * image_url'den tam erişilebilir bir URL oluşturur.
     */
    protected function imageFullUrl(): Attribute
    {
        $imagePath = 'president-message-images'; 
        return Attribute::make(
            get: fn () => $this->image_url ? asset('storage/' . $imagePath . '/128x128/' . $this->image_url) : null,
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (PresidentMessage $message) {
            if ($message->image_url) {
                // ImageService'i güvenli bir şekilde çağırmak için
                // app(ImageService::class) kullanabiliriz veya bir helper.
                try {
                     $imageService = app(ImageService::class);
                     $imagePath = 'president-message-images';
                     $sizes = ['800x600', '400x300', '128x128']; // Controller'da tanımladığımız boyutlar
                     $imageService->deleteImages($message->image_url, $imagePath, $sizes);
                } catch (\Exception $e) {
                    // Log error or ignore if service not available
                }
            }
        });
    }
}
