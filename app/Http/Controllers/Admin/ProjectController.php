<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseResourceController; // Eklendi
use App\Models\Project; // Otomatik geldi (veya ekle)
use App\Models\Gallery; // Galeri listesi için EKLENDİ
use Illuminate\Http\Request; // Otomatik geldi (veya ekle)
use Illuminate\Database\Eloquent\Model; // Eklendi

class ProjectController extends BaseResourceController // Değiştirildi
{

// Gerekli ayar metodları
protected function getModelInstance(): Model { return new Project(); }
protected function getViewPath(): string { return 'projects'; } // View klasör adı
protected function getRouteName(): string { return 'projects'; } // Rota adı öneki

// app/Http/Controllers/Admin/ProjectController.php içine eklenecek

protected function getValidationRules(Request $request, $id = null): array {
    return [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => $id ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date', // Bitiş tarihi başlangıçtan sonra olmalı
        'client' => 'required|string|max:255',
        'project_manager' => 'required|string|max:255',
        'location' => 'nullable|string|max:255',
        'project_type' => 'nullable|string|max:255',
        'gallery_id' => 'nullable|exists:galleries,id', // Galeri ID'si galleries tablosunda var olmalı
    ];
}

// app/Http/Controllers/Admin/ProjectController.php içine eklenecek

protected function getImageFieldName(): ?string { return 'image'; }
protected function getImagePath(): ?string { return 'project-images'; } // Kaydedilecek klasör
protected function getImageSizes(): array {
    // Modeldeki $sizes ile aynı olmalı
    return ['1920x1080', '600x400', '128x128'];
}

// app/Http/Controllers/Admin/ProjectController.php içine eklenecek

protected function getAdditionalDataForForms(): array {
    // Aktif olan galerileri isme göre sıralayarak forma gönderiyoruz.
    return [
        'galleries' => Gallery::where('status', true)->orderBy('name')->get(),
    ];
}

// app/Http/Controllers/Admin/ProjectController.php içine eklenecek

public function edit($id)
{
    $project = Project::findOrFail($id);
    // Modeldeki accessor sayesinde 'image_full_url' otomatik eklenecek
    return response()->json(['item' => $project]);
}

}
