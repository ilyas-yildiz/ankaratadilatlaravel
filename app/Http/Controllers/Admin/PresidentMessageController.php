<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseResourceController;
use App\Models\PresidentMessage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class PresidentMessageController extends BaseResourceController
{
    // Gerekli ayar metodları
    protected function getModelInstance(): Model { return new PresidentMessage(); }
    protected function getViewPath(): string { return 'president_messages'; } // View klasör adı
    protected function getRouteName(): string { return 'president-messages'; } // Rota adı öneki

    // Formdan gelen veriyi doğrulamak için kurallar
    protected function getValidationRules(Request $request, $id = null): array {
        return [
            'title' => 'required|string|max:255',
            'short_content' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'image' => $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ];
    }

    // Görsel işlemleri için ayarlar
    protected function getImageFieldName(): ?string { return 'image'; }
    protected function getImagePath(): ?string { return 'president-message-images'; } // Kaydedilecek klasör
    protected function getImageSizes(): array {
        return ['800x600', '400x300', '128x128']; 
    }

    protected function getAdditionalDataForForms(): array {
        return [];
    }

    public function edit($id)
    {
        $item = PresidentMessage::findOrFail($id);
        return response()->json(['item' => $item]);
    }

    /**
     * Listeleme metodu (AboutController gibi eziliyor, order kullanmamak için)
     */
    public function index()
    {
        $data = $this->model->all(); 
        $routeName = $this->getRouteName();
        $viewPath = $this->getViewPath();
        $additionalData = $this->getAdditionalDataForForms();

        return view('admin.' . $viewPath . '.index', compact('data', 'routeName', 'viewPath') + $additionalData);
    }
}
