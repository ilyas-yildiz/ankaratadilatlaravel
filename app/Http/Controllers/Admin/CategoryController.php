<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Services\ImageService; // Servisi dahil et

class CategoryController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        // Hiyerarşik görünüm için parent ve children'ı eager load yapabiliriz
        // Ancak düz listede parent ismini göstermek yeterli olacaktır.
        $categories = Category::with('parent')->orderBy('order')->get();
        
        // Modal içindeki select box için tüm kategorileri gönderelim
        $parentCategories = Category::where('status', true)->orderBy('name')->get();

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['image']);
        $data['slug'] = Str::slug($request->name);
        $data['type'] = 'blog';
        $data['color'] = $request->color ?? '#405189';

        // Görsel Yükleme
        if ($request->hasFile('image')) {
            // 3 farklı boyutta kaydedelim: Banner, Liste, Thumbnail
            $sizes = ['800x600', '400x300', '100x100'];
            $data['image_url'] = $this->imageService->saveImage($request->file('image'), 'category-images', $sizes);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        // Kendisi hariç diğer kategorileri parent listesi olarak gönder (döngüsel hatayı önlemek için)
        $parentCategories = Category::where('id', '!=', $id)->orderBy('name')->get();

        if (request()->ajax()) {
            return view('admin.categories.modals._form', compact('category', 'parentCategories'));
        }
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'meta_title' => 'nullable|string|max:70',
        ]);

        // Kendi kendini parent seçemez kontrolü
        if ($request->parent_id == $category->id) {
            return back()->with('error', 'Bir kategori kendi kendisinin üst kategorisi olamaz.');
        }

        $data = $request->except(['image']);
        if ($request->name !== $category->name) {
            $data['slug'] = Str::slug($request->name);
        }
        $data['color'] = $request->color ?? '#405189';

        // Görsel Güncelleme
        if ($request->hasFile('image')) {
            $sizes = ['800x600', '400x300', '100x100'];
            // Eski görseli sil
            if ($category->image_url) {
                $this->imageService->deleteImages($category->image_url, 'category-images', $sizes);
            }
            // Yeni görseli yükle
            $data['image_url'] = $this->imageService->saveImage($request->file('image'), 'category-images', $sizes);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(Category $category)
    {
        // Modeldeki booted metodu görseli otomatik silecek
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla silindi.');
    }
}