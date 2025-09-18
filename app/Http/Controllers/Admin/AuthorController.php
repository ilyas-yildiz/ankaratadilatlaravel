<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Services\ImageService; // ImageService'i kullanacağımızı belirtiyoruz
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // Loglama için

class AuthorController extends Controller
{
    // ImageService'i bu controller'da kullanılabilir hale getirmek için dependency injection kullanıyoruz.
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Tüm yazarları listeler.
     */
    public function index()
    {
        $authors = Author::orderBy('order', 'asc')->get();
        return view('admin.authors.index', compact('authors'));
    }

    public function store(Request $request)
    {
        // Doğrulama hataları, AJAX isteği olduğu için Laravel tarafından otomatik olarak JSON formatında döndürülür.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'img_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['title']);

        if ($request->hasFile('img_url')) {
            $sizes = ['263x272', '100x100'];
            $filename = $this->imageService->saveImage($request->file('img_url'), 'authors', $sizes);
            if ($filename) {
                $validatedData['img_url'] = $filename;
            }
        }

        Author::create($validatedData);

        // DEĞİŞİKLİK: redirect() yerine, JavaScript'in anlayacağı bir JSON yanıtı döndürüyoruz.
        return response()->json(['success' => true, 'message' => 'Yazar başarıyla oluşturuldu.']);
    }

    /**
     * Mevcut bir yazar kaydını AJAX isteğine JSON yanıtı vererek günceller.
     */
    public function update(Request $request, Author $author)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'img_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($author->title !== $validatedData['title']) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        if ($request->hasFile('img_url')) {
            $sizes = ['263x272', '100x100'];
            if ($author->img_url) {
                $this->imageService->deleteImages($author->img_url, 'authors', $sizes);
            }
            $filename = $this->imageService->saveImage($request->file('img_url'), 'authors', $sizes);
            if ($filename) {
                $validatedData['img_url'] = $filename;
            }
        }

        $author->update($validatedData);

        // DEĞİŞİKLİK: redirect() yerine, JavaScript'in anlayacağı bir JSON yanıtı döndürüyoruz.
        return response()->json(['success' => true, 'message' => 'Yazar başarıyla güncellendi.']);
    }

    /**
     * Bir yazarı ve ImageService kullanarak ilişkili tüm görsellerini siler.
     */
    public function destroy(Author $author)
    {
        // Yazara ait bir görsel varsa...
        if ($author->img_url) {
            // ...ImageService'i kullanarak tüm boyutlardaki görselleri sil.
            $sizes = ['263x272', '100x100'];
            $this->imageService->deleteImages($author->img_url, 'authors', $sizes);
        }

        // Veritabanındaki yazar kaydını sil.
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Yazar başarıyla silindi.');
    }
}

