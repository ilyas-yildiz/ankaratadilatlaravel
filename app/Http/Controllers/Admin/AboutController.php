<?php

namespace App\Http\Controllers\Admin;

// GÜNCELLENDİ: BaseResourceController'ı kullan
use App\Http\Controllers\Admin\BaseResourceController; 
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model; // Model'i dahil et
use Illuminate\Support\Str; // Str'ı dahil et (eğer slug vs. kullanacaksan)

// GÜNCELLENDİ: Miras alınan sınıfı değiştir
class AboutController extends BaseResourceController 
{

    // Gerekli ayar metodları
    protected function getModelInstance(): Model { return new About(); }
    protected function getViewPath(): string { return 'abouts'; } // View klasör adı
    protected function getRouteName(): string { return 'abouts'; } // Rota adı öneki

    // Formdan gelen veriyi doğrulamak için kurallar
    protected function getValidationRules(Request $request, $id = null): array {
        return [
            'title' => 'required|string|max:255',
            'short_content' => 'nullable|string|max:1000', // Maksimum karakter sayısını ayarla
            'content' => 'required|string',
            // Görsel için kurallar (BlogController'daki gibi)
            'image' => $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ];
    }

    // Görsel işlemleri için ayarlar (BlogController'daki gibi, yolları değiştir)
    protected function getImageFieldName(): ?string { return 'image'; }
    protected function getImagePath(): ?string { return 'about-images'; } // Kaydedilecek klasör
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
        $about = About::findOrFail($id);
        // Modeldeki accessor sayesinde 'image_full_url' otomatik eklenecek
        return response()->json(['item' => $about]);
    }

 // YENİ EKLENDİ: BaseResourceController'daki index metodunu eziyoruz (override).
    /**
     * Hakkımızda sayfasının içeriğini listeler.
     * BaseController'ın varsayılan 'order' sıralamasını kullanmaz.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Verileri 'order' sütununa göre sıralamadan çekiyoruz.
        // Genellikle tek kayıt olacağı için all() yeterli.
        // İstersen orderBy('id', 'asc') veya orderBy('created_at', 'asc') de kullanabilirsin.
        $data = $this->model->all(); 

        $routeName = $this->getRouteName();
        $viewPath = $this->getViewPath();
        
        // Forma gönderilecek ek verileri alıyoruz (bizim durumumuzda boş)
        $additionalData = $this->getAdditionalDataForForms();

        // Veriyi view'a gönderiyoruz (BaseController'daki gibi)
        return view('admin.' . $viewPath . '.index', compact('data', 'routeName', 'viewPath') + $additionalData);
    }


}
