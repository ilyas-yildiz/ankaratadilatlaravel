<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseResourceController extends Controller
{
    protected Model $model;
    protected ImageService $imageService;

    // Her alt sınıf kendi modelini, view yolunu ve route adını belirtmek zorunda.
    abstract protected function getModelInstance(): Model;
    abstract protected function getViewPath(): string;
    abstract protected function getRouteName(): string;
    abstract protected function getValidationRules(Request $request, $id = null): array;

    // Resim işlemleri için ayarlar (alt sınıflar bunları ezer)
    protected function getImageFieldName(): ?string { return null; } // Resim alanının adı, ör: 'image'
    protected function getImagePath(): ?string { return null; }     // Resimlerin kaydedileceği dizin, ör: 'blog-images'
    protected function getImageSizes(): array { return []; }         // Resim boyutları

    public function __construct(ImageService $imageService)
    {
        $this->model = $this->getModelInstance();
        $this->imageService = $imageService;
    }

    public function index()
    {
        // getAdditionalDataForIndex metodu, her modülün index'e özel veri göndermesini sağlar
        $additionalData = array_merge(
            $this->getAdditionalDataForIndex(),
            $this->getAdditionalDataForForms()
        );

        return view("admin.{$this->getViewPath()}.index", [
            'data' => $this->model->orderBy('order', 'asc')->get(),
            'viewPath' => $this->getViewPath(),
            'routeName' => $this->getRouteName(),
            ...$additionalData
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->getValidationRules($request));

        if (isset($validatedData['title']) && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug')) {
        $validatedData['slug'] = Str::slug($validatedData['title']);
        // Slug'ın benzersizliğini kontrol et ve gerekirse ekle (_1, _2 gibi) - İsteğe bağlı, daha gelişmiş
    }

        if ($this->getImageFieldName() && $request->hasFile($this->getImageFieldName())) {
            $filename = $this->imageService->saveImage(
                $request->file($this->getImageFieldName()),
                $this->getImagePath(),
                $this->getImageSizes()
            );
            // Modelde resim sütununun adı 'image_url' varsayılıyor.
            // Farklıysa bu kısım da jenerikleştirilebilir.
            if ($filename) {
                $validatedData['image_url'] = $filename;
            }
        }

        $this->model->create($validatedData);
        return response()->json(['success' => true, 'message' => 'Kayıt başarıyla oluşturuldu.']);
    }

    public function edit($id)
    {
        $item = $this->model->findOrFail($id);

        // Eğer bu modelde bir resim alanı varsa ve doluysa...
        if ($this->getImageFieldName() && $item->image_url) {
            // Admin panelinde 128x128 boyutunu göstereceğimiz için yolu buna göre oluşturuyoruz.
            $item->image_full_url = asset('storage/' . $this->getImagePath() . '/128x128/' . $item->image_url);
        }

        return response()->json($item);
    }

 public function update(Request $request, $id)
    {
        $item = $this->model->findOrFail($id);
        $validatedData = $request->validate($this->getValidationRules($request, $id));
        $hasSlugColumn = $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug');

         // GÜNCELLENDİ: Slug oluşturma/güncelleme mantığı
        if (isset($validatedData['title']) && $hasSlugColumn) {
             // Eğer slug boşsa VEYA başlık değişmişse slug'ı yeniden oluştur/güncelle
            if (empty($item->slug) || $item->title !== $validatedData['title']) { 
                 $validatedData['slug'] = Str::slug($validatedData['title']);
                 // İsteğe bağlı: Benzersizlik kontrolü eklenebilir
                 // Örn: $validatedData['slug'] = $this->makeUniqueSlug($validatedData['slug'], $id);
            }
        }

        if ($this->getImageFieldName() && $request->hasFile($this->getImageFieldName())) {
            if ($item->image_url) {
                // ImageService'i app() ile alalım (store'daki gibi)
                app(ImageService::class)->deleteImages($item->image_url, $this->getImagePath(), $this->getImageSizes());
            }
            $filename = app(ImageService::class)->saveImage(
                $request->file($this->getImageFieldName()),
                $this->getImagePath(),
                $this->getImageSizes()
            );
            if ($filename) {
                $validatedData['image_url'] = $filename;
            } else {
                 // Hata JSON'ı döndür
                return response()->json(['success' => false, 'message' => $this->getResourceName() . ' görseli güncellenemedi.'], 422);
            }
        }

        $item->update($validatedData);
         // Başarı JSON'ı aynı
        return response()->json(['success' => true, 'message' => $this->getResourceName() . ' başarıyla güncellendi.']);
    }

    public function destroy($id)
    {
        $item = $this->model->findOrFail($id);

        // Resim varsa sil
        if ($this->getImageFieldName() && $item->image_url) {
            $this->imageService->deleteImages($item->image_url, $this->getImagePath(), $this->getImageSizes());
        }

        $item->delete();
        return redirect()->route("admin.{$this->getRouteName()}.index")->with('success', 'Kayıt başarıyla silindi.');
    }

    // Alt sınıfların index veya formlar için ek veri göndermesini sağlayan boş metodlar
    protected function getAdditionalDataForIndex(): array { return []; }
    protected function getAdditionalDataForForms(): array { return []; }
    // YENİ: getResourceName metodu (SlideController'dan buraya taşıdım, daha merkezi)
protected function getResourceName(): string
{
    return Str::singular(Str::studly($this->getRouteName()));
}

}
