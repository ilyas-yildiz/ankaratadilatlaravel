<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Admin paneli anasayfasını gösterir ve gerekli verileri derler.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // 1. Üstteki büyük kartlar için temel istatistikleri çekiyoruz.
        $totalBlogs = Blog::count();
        $totalAuthors = Author::count();
        $totalCategories = Category::count();

        // 2. "En Son Eklenen Yazılar" tablosu için son 5 blogu ilişkileriyle birlikte çekiyoruz.
        // 'with()' kullanımı, N+1 sorgu problemini önleyerek performansı artırır.
        $latestBlogs = Blog::with(['category', 'author'])->latest()->take(5)->get();

        // 3. "En Aktif Yazarlar" tablosu için, en çok yazısı olan 5 yazarı çekiyoruz.
        // 'withCount()' metodu, her yazara ait 'blogs_count' adında bir özellik ekler.
        $activeAuthors = Author::withCount('blogs')->orderBy('blogs_count', 'desc')->take(5)->get();

        // 4. Sağ kenar çubuğundaki "Kategoriye Göre Yazı Sayısı" listesi için.
        $categoriesWithCount = Category::withCount('blogs')->orderBy('blogs_count', 'desc')->take(5)->get();

        // Tüm verileri view'a 'compact' fonksiyonu ile gönderiyoruz.
        return view('admin.dashboard.index', compact(
            'totalBlogs',
            'totalAuthors',
            'totalCategories',
            'latestBlogs',
            'activeAuthors',
            'categoriesWithCount'
        ));
    }
}
