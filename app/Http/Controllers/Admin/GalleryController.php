<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Support\Facades\Response; // Response sınıfını ekliyoruz

class GalleryController extends Controller
{
    /**
     * Tüm galerileri listele.
     */
    public function index()
    {
        $galleries = Gallery::orderBy('order')->get();
        return view('admin.galleries.index', compact('galleries'));
    }

    /**
     * Yeni galeri oluşturma formunu göster.
     */
    public function create()
    {
        return view('admin.galleries.create');
    }

    /**
     * Yeni bir galeriyi veritabanına kaydet.
     */
    public function store(Request $request)
    {
        // Sadece 'name' alanı için doğrulama
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Galeri slug'ını oluştur
        $slug = Str::slug($request->name);

        // Galeri oluştur
        Gallery::create([
            'name' => $request->name,
            'slug' => $slug,
            'status' => 1,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri başarıyla oluşturuldu.');
    }

    /**
     * Belirli bir galeriyi ve resimlerini göster.
     */
    public function show(Gallery $gallery)
    {
        // Bu metod artık kullanılmıyor.
    }

    /**
     * Galeriyi düzenleme formunu göster.
     */
    public function edit(Gallery $gallery)
    {
        // Galeriye ait resimleri yükle
        $gallery->load('items');
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Galeriyi güncelle.
     */
    public function update(Request $request, Gallery $gallery)
    {
        // Doğrulamayı yalnızca "name" alanı için yap
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Galeri adını ve slug'ını güncelle
        $gallery->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri başarıyla güncellendi.');
    }

    /**
     * Galeriyi ve ilgili resimlerini sil.
     */
    public function destroy(Gallery $gallery)
    {
        // Resim dosyalarını storage'dan sil
        foreach ($gallery->items as $item) {
            Storage::disk('public')->delete($item->image);
        }

        // Galeri ve resimlerini sil (onDelete('cascade') sayesinde gallery_items silinir)
        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri başarıyla silindi.');
    }

    /**
     * Kapak görselini günceller.
     * @param Request $request
     * @param Gallery $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoverImage(Request $request, Gallery $gallery)
    {
        // İsteğin doğrulanması
        $validatedData = $request->validate([
            'cover_image_id' => 'nullable|integer',
        ]);

        $coverImageId = $validatedData['cover_image_id'];
        $coverImagePath = null;

        // Eğer bir kapak görseli ID'si geldiyse, yolu bul
        if ($coverImageId) {
            $galleryItem = GalleryItem::find($coverImageId);
            if ($galleryItem) {
                $coverImagePath = $galleryItem->image;
            }
        }

        // Galeri modelini güncelle
        $gallery->cover_image = $coverImagePath;
        $gallery->save();

        // Başarılı bir yanıt dön
        return Response::json([
            'success' => true,
            'message' => 'Kapak görseli başarıyla güncellendi.'
        ]);
    }
}
