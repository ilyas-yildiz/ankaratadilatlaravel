<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseResourceController;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService;
use Illuminate\Support\Str;

class SlideController extends BaseResourceController // Değiştirildi
{
    // app/Http/Controllers/Admin/SlideController.php içine eklenecek

    // Gerekli ayar metodları
    protected function getModelInstance(): Model
    {
        return new Slide();
    }
    protected function getViewPath(): string
    {
        return 'slides';
    } // View klasör adı
    protected function getRouteName(): string
    {
        return 'slides';
    } // Rota adı öneki

    // app/Http/Controllers/Admin/SlideController.php içine eklenecek

    protected function getValidationRules(Request $request, $id = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255', // Linkin geçerli bir URL olmasını kontrol edelim
            'button_text' => 'nullable|string|max:50',
            // Görsel zorunlu, ilk kayıtta gerekli, güncellemede isteğe bağlı
            'image' => $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240' : 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_sketch' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ];
    }

    // app/Http/Controllers/Admin/SlideController.php içine eklenecek

    protected function getImageFieldName(): ?string
    {
        return 'image';
    }
    protected function getImagePath(): ?string
    {
        return 'slide-images';
    }
    protected function getImageSizes(): array
    {
        return ['1920x1080', '1280x720', '128x128'];
    }

    // YENİ: Sketch Görsel Ayarları (Yardımcı metodlar)
    protected function getSketchImageFieldName(): string
    {
        return 'image_sketch';
    }
    protected function getSketchImagePath(): string
    {
        return 'slide-images-sketch';
    }
    protected function getSketchImageSizes(): array
    {
        return $this->getImageSizes();
    } // Şimdilik aynı boyutlar

    // app/Http/Controllers/Admin/SlideController.php içine eklenecek

    protected function getAdditionalDataForForms(): array
    {
        return [];
    }

    // --- STORE METODU OVERRIDE EDİLDİ ---
    public function store(Request $request)
    {
        $imageService = app(ImageService::class);
        $validatedData = $request->validate($this->getValidationRules($request));

        // Ana görseli işle (saveImage kullanarak)
        if ($request->hasFile($this->getImageFieldName())) {
            // DÜZELTİLDİ: storeAndResize -> saveImage
            $filename = $imageService->saveImage(
                $request->file($this->getImageFieldName()),
                $this->getImagePath(),
                $this->getImageSizes()
            );
            // DÜZELTİLDİ: Dönen değeri kontrol et
            if ($filename) {
                $validatedData['image_url'] = $filename;
            } else {
                // saveImage başarısız olursa (varsayım: null döner)
                return back()->withErrors(['image' => 'Ana görsel kaydedilemedi.'])->withInput();
            }
        }

        // Sketch görselini işle (saveImage kullanarak)
        if ($request->hasFile($this->getSketchImageFieldName())) {
            // DÜZELTİLDİ: storeAndResize -> saveImage
            $sketchFilename = $imageService->saveImage(
                $request->file($this->getSketchImageFieldName()),
                $this->getSketchImagePath(),
                $this->getSketchImageSizes()
            );
            // DÜZELTİLDİ: Dönen değeri kontrol et
            if ($sketchFilename) {
                $validatedData['image_sketch_url'] = $sketchFilename;
            } else {
                // Eğer ana görsel kaydedildiyse onu silmek iyi olabilir
                if (isset($validatedData['image_url'])) {
                    $imageService->deleteImages($validatedData['image_url'], $this->getImagePath(), $this->getImageSizes());
                }
                return back()->withErrors(['image_sketch' => 'Sketch görsel kaydedilemedi.'])->withInput();
            }
        }

        $validatedData['order'] = ($this->model->max('order') ?? -1) + 1;
        $this->model->create($validatedData);

        return response()->json(['success' => true, 'message' => $this->getResourceName() . ' başarıyla eklendi.']);
    }

    // --- UPDATE METODU OVERRIDE - DOĞRU METOT ADI KULLANILDI ---
    public function update(Request $request, $id)
    {
        $imageService = app(ImageService::class);
        $item = $this->model->findOrFail($id);
        $validatedData = $request->validate($this->getValidationRules($request, $id));

        // Ana görseli güncelle (saveImage kullanarak)
        if ($request->hasFile($this->getImageFieldName())) {
            // DÜZELTİLDİ: storeAndResize -> saveImage
            $filename = $imageService->saveImage(
                $request->file($this->getImageFieldName()),
                $this->getImagePath(),
                $this->getImageSizes()
            );
            // DÜZELTİLDİ: Dönen değeri kontrol et
            if ($filename) {
                if ($item->image_url) {
                    $imageService->deleteImages($item->image_url, $this->getImagePath(), $this->getImageSizes());
                }
                $validatedData['image_url'] = $filename;
            } else {
                return back()->withErrors(['image' => 'Ana görsel güncellenemedi.'])->withInput();
            }
        }

        // Sketch görselini güncelle (saveImage kullanarak)
        if ($request->hasFile($this->getSketchImageFieldName())) {
            // DÜZELTİLDİ: storeAndResize -> saveImage
            $sketchFilename = $imageService->saveImage(
                $request->file($this->getSketchImageFieldName()),
                $this->getSketchImagePath(),
                $this->getSketchImageSizes()
            );
            // DÜZELTİLDİ: Dönen değeri kontrol et
            if ($sketchFilename) {
                if ($item->image_sketch_url) {
                    $imageService->deleteImages($item->image_sketch_url, $this->getSketchImagePath(), $this->getSketchImageSizes());
                }
                $validatedData['image_sketch_url'] = $sketchFilename;
            } else {
                // Eğer yeni ana görsel kaydedildiyse onu silmek iyi olabilir
                if ($request->hasFile($this->getImageFieldName()) && isset($validatedData['image_url'])) {
                    $imageService->deleteImages($validatedData['image_url'], $this->getImagePath(), $this->getImageSizes());
                }
                return response()->json(['success' => false, 'message' => 'Sketch görsel güncellenemedi.'], 422);
            }
        }

        $item->update($validatedData);

        // DÜZELTİLDİ: Başarılı güncelleme sonrası JSON yerine redirect yapalım (store gibi)
        return response()->json(['success' => true, 'message' => $this->getResourceName() . ' başarıyla güncellendi.']);
    }

    protected function getResourceName(): string
    {
        // Route adından otomatik olarak bir isim türetir (örn: 'slides' -> 'Slide')
        return Str::singular(Str::studly($this->getRouteName()));
    }

    public function edit($id)
    {
        $slide = Slide::findOrFail($id);
        // Modeldeki accessor sayesinde 'image_full_url' otomatik eklenecek
        return response()->json(['item' => $slide]);
    }
}