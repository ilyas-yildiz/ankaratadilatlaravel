<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService; // 1. ImageService'i dahil ediyoruz.
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'sku',
        'content',
        'image_url',
        'gallery_id',
        'status',
        'order',
    ];

    /**
     * Modelin olay haritasını kaydetmek için kullanılır.
     * Bu metot, model her başlatıldığında otomatik olarak çalışır.
     */
    protected static function booted(): void
    {
        // "deleting" olayı, bir model kalıcı olarak silinmeden HEMEN ÖNCE tetiklenir.
        static::deleting(function (Product $product) {
            try {
                // 2. Eğer ürünün bir resmi varsa, ImageService'i çağırıp siliyoruz.
                if ($product->image_url) {
                    // Servisi Laravel'in servis container'ından alıyoruz.
                    $imageService = app(ImageService::class);
                    // Resim boyutlarını Controller'dan kopyalayıp buraya da ekleyebiliriz.
                    $sizes = ['1124x790', '562x395', '274x183', '128x128']; // İhtiyaca göre diğer boyutları ekleyin.
                    $imageService->deleteImages($product->image_url, 'product-images', $sizes);
                }
            } catch (\Exception $e) {
                // Resim silinirken bir hata olursa işlemi durdurma, sadece logla.
                Log::error("Ürün resmi silinirken hata oluştu (Product ID: {$product->id}): " . $e->getMessage());
            }
        });
    }
}
