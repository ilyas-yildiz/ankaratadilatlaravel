<?php

namespace App\Http\Controllers\Admin;

// GÜNCELLENDİ: BaseResourceController'ı kullan
use App\Http\Controllers\Admin\BaseResourceController; 
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model; // Model'i dahil et
use Illuminate\Support\Str; // Str'ı dahil et (eğer slug vs. kullanacaksan)

// GÜNCELLENDİ: Miras alınan sınıfı değiştir
class ServiceController extends BaseResourceController 
{

    // Gerekli ayar metodları
    protected function getModelInstance(): Model { return new Service(); }
    protected function getViewPath(): string { return 'services'; } // View klasör adı
    protected function getRouteName(): string { return 'services'; } // Rota adı öneki

    // Formdan gelen veriyi doğrulamak için kurallar
    protected function getValidationRules(Request $request, $id = null): array {
        return [
        'title' => 'required|string|max:255',
        'content' => 'required|string', // Standart 'content' adı
        'image' => $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        // 'order' alanı sıralama işlemiyle otomatik ayarlanacağı için burada doğrulamaya gerek yok.
    ];
    }

    // Görsel işlemleri için ayarlar (BlogController'daki gibi, yolları değiştir)
    protected function getImageFieldName(): ?string { return 'image'; }
    protected function getImagePath(): ?string { return 'service-images'; } // Kaydedilecek klasör
    protected function getImageSizes(): array { 
        // Hakkımızda için farklı boyutlar gerekiyorsa güncelle
        return ['800x600', '400x300', '128x128']; 
    } 

    // Forma ek veri göndermek (şimdilik boş, kategori vs. yok)
    protected function getAdditionalDataForForms(): array {
        return [];
    }

    // Düzenleme modalı için JSON verisi döndüren edit metodu (BlogController'daki gibi)
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        // Modeldeki accessor sayesinde 'image_full_url' otomatik eklenecek
        return response()->json(['item' => $service]);
    }



}
