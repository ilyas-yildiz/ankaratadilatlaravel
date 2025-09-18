<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Parsedown;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseResourceController
{
    // 1. Ayar: Hangi modeli kullanacağını belirt
    protected function getModelInstance(): Model
    {
        return new Product();
    }

    // 2. Ayar: View dosyalarının hangi klasörde olduğunu belirt
    protected function getViewPath(): string
    {
        return 'products';
    }

    // 3. Ayar: Rota isimlerinin ne olduğunu belirt (örn: admin.blogs.index)
    protected function getRouteName(): string
    {
        return 'products';
    }

    // 4. Ayar: Validasyon kurallarını tanımla
    protected function getValidationRules(Request $request, $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $id,
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'gallery_id' => 'nullable|exists:galleries,id', // 2. gallery_id için doğrulama kuralı ekledik.
            'slug' => 'nullable|string', // Slug'ı store/update içinde biz oluşturacağız
        ];

        // Sadece yeni kayıt eklerken resim zorunlu olsun, güncellemede olmasın.
        $rules['image'] = $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072';

        return $rules;
    }

    // 5. Ayar (Resimler için): Resim ayarlarını yap
    protected function getImageFieldName(): ?string { return 'image'; }
    protected function getImagePath(): ?string { return 'product-images'; }
    protected function getImageSizes(): array { return ['1124x790', '562x395', '274x183', '128x128']; }

    // 6. Ayar: Formlar (create/edit modal) için gerekli ek verileri yolla
    protected function getAdditionalDataForForms(): array
    {
        return [
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'galleries' => Gallery::where('status', true)->orderBy('name')->get(),
        ];
    }

    // --- STANDART DIŞI, BLOG'A ÖZEL MANTIKLAR ---

    // Slug oluşturma mantığı Blog'a özel olduğu için store ve update'i eziyoruz.
    public function store(Request $request)
    {
        $request->merge(['slug' => Str::slug($request->name)]);
        return parent::store($request); // Ana controller'ın store metodunu çağır
    }

    public function update(Request $request, $id)
    {
        $item = $this->model->findOrFail($id);
        if ($request->name !== $item->name) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }
        return parent::update($request, $id); // Ana controller'ın update metodunu çağır
    }

    // İçerik getirme metodu
    public function editContent(Blog $blog)
    {
        return response()->json(['content' => $blog->content]);
    }
}
