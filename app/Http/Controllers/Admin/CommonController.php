<?php

// app/Http/Controllers/Admin/CommonController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // Hata ayıklama için Log sınıfını dahil ediyoruz
use Throwable; // Her türlü hatayı yakalamak için

class CommonController extends Controller
{
    /**
     * Güvenlik için toplu silmeye izin verilen modellerin ve sınıf yollarının listesi.
     * Yeni bir modül eklediğinizde sadece bu diziye eklemeniz yeterlidir.
     */
    /**
     * Güvenlik için toplu silmeye izin verilen modellerin listesi.
     * DİKKAT: Buraya sadece projenizde VAR OLAN modelleri ekleyin.
     * Blog veya Category modeliniz yoksa bu satırları silin veya yorum satırı yapın.
     */
    private function getAllowedModels()
    {
        return [
            'authors' => \App\Models\Author::class,
            'blogs' => \App\Models\Blog::class,
            'products' => \App\Models\Product::class,
            'slides'     => \App\Models\Slide::class, // << YENİ EKLENEN SATIR
            // 'categories' => \App\Models\Category::class, // Eğer Category modeliniz varsa bu satırı aktif edin
        ];
    }

    public function updateStatus(Request $request, string $modelName, int $id)
    {
        $allowedModels = $this->getAllowedModels();

        // 1. GÜVENLİK: Modelin izin verilenler listesinde olduğunu doğrula.
        if (!array_key_exists($modelName, $allowedModels)) {
            return response()->json(['success' => false, 'message' => 'Geçersiz model adı.'], 403);
        }

        // 2. DOĞRU VERİ TİPİ: Gelen "1" veya "0" string'ini gerçek true/false değerine çevir.
        $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);

        try {
            $modelClass = $allowedModels[$modelName];
            $item = $modelClass::findOrFail($id);

            // 3. ATAMA VE KAYDETME: Doğru veri tipindeki status'u ata ve kaydet.
            $item->status = $status;
            $item->save();

            // 4. BAŞARILI YANIT: JavaScript'e işlemin başarılı olduğunu ve iziToast'u göstermesi gerektiğini söyle.
            return response()->json(['success' => true, 'message' => 'Durum başarıyla güncellendi.']);

        } catch (Throwable $e) {
            Log::error("Durum güncelleme hatası ({$modelName} - ID: {$id}): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'İşlem sırasında bir sunucu hatası oluştu.'], 500);
        }
    }

    /**
     * İlgili modelin sıralamasını günceller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $modelName
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request, $modelName)
    {
        // Model ismini uygun bir formata dönüştür
        $modelClass = 'App\\Models\\' . Str::singular(Str::studly($modelName));

        if (! class_exists($modelClass)) {
            return response()->json(['success' => false, 'message' => 'Model bulunamadı.'], 404);
        }

        $orderData = $request->input('order');

        foreach ($orderData as $item) {
            $model = $modelClass::findOrFail($item['id']);
            $model->order = $item['position'];
            $model->save();
        }

        return response()->json(['success' => true, 'message' => 'Sıralama başarıyla güncellendi.']);
    }

    public function bulkDestroy(Request $request, $modelName)
    {
        $allowedModels = $this->getAllowedModels();

        // 1. GÜVENLİK: Gelen model adının izin verilenler listesinde olup olmadığını kontrol et.
        if (!array_key_exists($modelName, $allowedModels)) {
            return response()->json(['success' => false, 'message' => 'Geçersiz işlem.'], 403);
        }

        $modelClass = $allowedModels[$modelName];
        $tableName = (new $modelClass)->getTable(); // Modelden tablo adını alıyoruz (örn: 'authors')

        // 2. DOĞRULAMA: Gelen ID'lerin geçerli olduğundan ve veritabanında var olduğundan emin ol.
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => "integer|exists:{$tableName},id", // Her ID'nin ilgili tabloda var olduğunu kontrol et.
        ]);

        $ids = $validated['ids'];

        try {
            // 3. KAYITLARI BUL VE SİL
            // Modelleri önce koleksiyon olarak çekiyoruz.
            $modelsToDelete = $modelClass::whereIn('id', $ids)->get();

            // Bir döngü içinde her bir modeli tek tek siliyoruz.
            // Bu, her model için 'deleting' olayının (görsel silme vb.) tetiklenmesini sağlar.
            foreach ($modelsToDelete as $model) {
                $model->delete();
            }

            // 4. BAŞARI YANITI
            return response()->json(['success' => true, 'message' => 'Seçilen kayıtlar başarıyla silindi.']);

        } catch (Throwable $e) {
            // 5. HATA YAKALAMA
            Log::error("Toplu silme hatası ({$modelName}): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Silme işlemi sırasında bir sunucu hatası oluştu.'], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            // Resmi 'public/editor-uploads' klasörüne kaydet
            $path = $request->file('file')->store('editor-uploads', 'public');

            // TinyMCE'nin beklediği formatta JSON yanıtı döndür
            return response()->json(['location' => asset('storage/' . $path)]);

        } catch (\Exception $e) {
            Log::error('TinyMCE resim yükleme hatası: ' . $e->getMessage());
            return response()->json(['error' => 'Resim yüklenirken bir hata oluştu.'], 500);
        }
    }
}
