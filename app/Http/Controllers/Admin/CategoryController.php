<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sadece 'blog' tipindeki kategorileri çekelim
        $categories = Category::orderBy('order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Gelen verileri doğrulayalım
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Slug'ı oluşturalım ve kaydedelim
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => 'blog', // Kategorinin tipini sabit olarak belirleyelim
        ]);

        // Kullanıcıyı kategori listeleme sayfasına yönlendirelim
        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // Eğer AJAX isteği ise sadece partial döndür
        if (request()->ajax()) {
            return view('admin.categories.modals._form', compact('category'));
        }
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Gelen verileri doğrulayalım
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Slug'ı yeniden oluşturalım. Eğer isim değişmediyse aynı kalır.
        $slug = Str::slug($request->name);

        // Güncelleme işlemini yapalım
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        // Flash mesaj ekle
        return redirect()->route('admin.categories.index')
            ->with('success', 'Düzenleme işlemi başarıyla tamamlanmıştır.');

        // Kullanıcıyı kategori listeleme sayfasına yönlendirelim
        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        // Kullanıcıyı kategori listeleme sayfasına yönlendirelim
        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla silindi.');
    }

}
