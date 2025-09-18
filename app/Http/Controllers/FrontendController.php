<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Author;


class FrontendController extends Controller
{
    /**
     * Anasayfayı gösterir.
     */
    public function index()
    {
        // --- 1. Manşet Haberlerini Çekme (Mevcut Kod) ---
        $featuredBlogs = Blog::where('is_featured', true)
            ->where('status', true)
            ->latest()
            ->limit(5)
            ->get();

        $mainFeatured = $featuredBlogs->first();
        $subFeatured = $featuredBlogs->slice(1);

        // Manşetteki haberlerin ID'lerini bir dizide toplayalım ki tekrar göstermeyelim.
        $featuredBlogIds = $featuredBlogs->pluck('id');


        // --- 2. Kategori Haberlerini Çekme (Yeni Kod) ---
        // Anasayfada gösterilecek, durumu aktif olan kategorileri çekiyoruz.
        $categoriesWithBlogs = Category::where('status', true)
            ->where('type', 'blog')
            ->orderBy('order', 'asc')
            ->with(['blogs' => function ($query) use ($featuredBlogIds) {
                // Her bir kategori için, ilişkili blogları şu kurallarla çek:
                $query->where('status', true)
                    // Manşette gösterilen haberleri HARİÇ TUT
                    ->whereNotIn('id', $featuredBlogIds)
                    ->latest() // En yeniden eskiye sırala
                    ->limit(5); // Sadece 5 tane al
            }])
            ->get()
            // Sadece içinde en az 1 haber olan kategorileri al
            ->filter(function ($category) {
                return $category->blogs->isNotEmpty();
            });

        // Kategorileri ikişerli gruplara ayıralım (view'da kolaylık için)
        $categoryChunks = $categoriesWithBlogs->chunk(2);


        $sidebarAuthors = Author::where('status', true)
            ->orderBy('order', 'asc')
            ->limit(5) // Yan barda gösterilecek yazar sayısını 5 ile sınırlayalım.
            ->get();

        // --- 4. SON DAKİKA BARI İÇİN HABERLERİ ÇEKME (YENİ KOD) ---
        // Durumu aktif olan ve manşette GÖSTERİLMEYEN en son 10 haberi çekiyoruz.
        $tickerBlogs = Blog::where('status', true)
            ->whereNotIn('id', $featuredBlogIds)
            ->latest()
            ->limit(10)
            ->get();

        // --- 5. Tüm Verileri View'a Gönderme ---
        return view('frontend.pages.home', compact(
            'mainFeatured',
            'subFeatured',
            'categoryChunks',
            'sidebarAuthors',
            'tickerBlogs' // Yeni değişkeni view'a gönderiyoruz
        ));

        // --- 4. Tüm Verileri View'a Gönderme ---
        return view('frontend.pages.home', compact(
            'mainFeatured',
            'subFeatured',
            'categoryChunks',
            'sidebarAuthors' // Yeni değişkeni view'a gönderiyoruz
        ));
    }

    public function category($slug)
    {
        // 1. ANA İÇERİK: Mevcut kategoriyi ve ona ait haberleri çek (Sayfalanmış)
        $category = Category::where('slug', $slug)->where('status', true)->firstOrFail();
        $blogs = $category->blogs()
            ->where('status', true)
            ->latest()
            ->paginate(10);

        // 2. YAN BAR İÇERİĞİ (YENİ KOD): Tüm kategorileri, aktif haber sayılarıyla birlikte çek
        // withCount, her bir kategori için 'blogs' ilişkisindeki aktif haber sayısını sayar
        // ve bunu 'blogs_count' adında bir özelliğe ekler.
        $sidebarCategories = Category::where('status', true)
            ->where('type', 'blog')
            ->withCount(['blogs' => function ($query) {
                $query->where('status', true);
            }])
            ->orderBy('order', 'asc')
            ->get();


        // 3. Tüm verileri view'a gönder.
        return view('frontend.pages.category', compact('category', 'blogs', 'sidebarCategories'));
    }

    /**
     * Yazarlar sayfasını gösterir.
     */
    public function authors()
    {
        // Durumu aktif olan tüm yazarları 'order' sütununa göre sıralayarak çek.
        $authors = Author::where('status', true)
            ->orderBy('order', 'asc')
            ->get();

        // Çektiğimiz yazar verilerini 'frontend.pages.authors' view'ına gönder.
        return view('frontend.pages.author', compact('authors'));
    }

    public function authorDetail($slug)
    {
        // 1. URL'den gelen slug'a göre yazarı bul. Eğer bulunamazsa 404 hatası ver.
        $author = Author::where('slug', $slug)->firstOrFail();

        // 2. Bu yazara ait olan, durumu aktif olan blogları en yeniden eskiye sırala
        //    ve sayfalama uygula (sayfa başına 10 haber).
        // NOT: Bu kodun çalışması için Blog modelinde author_id sütunu olmalı
        // ve Author modelinde blogs() ilişkisi tanımlı olmalı.
        $blogs = $author->blogs()
            ->where('status', true)
            ->latest()
            ->paginate(10);

        // a) Tüm kategorileri, aktif haber sayılarıyla birlikte çek.
        $sidebarCategories = Category::where('status', true)
            ->where('type', 'blog')
            ->withCount(['blogs' => function ($query) {
                $query->where('status', true);
            }])
            ->orderBy('order', 'asc')
            ->get();

        // b) Yan barda gösterilecek yazarları çek.
        $sidebarAuthors = Author::where('status', true)
            ->orderBy('order', 'asc')
            ->limit(5)
            ->get();

        // 3. Bulunan yazar ve blog verilerini view'a gönder.
        return view('frontend.pages.author_detail', compact('author', 'blogs', 'sidebarCategories',
            'sidebarAuthors'));
    }

    public function blogDetail($slug)
    {
        // 1. Haberi slug'ına göre bul. Durumu aktif olmalı. İlişkili verileri (kategori, yazar) de birlikte çek.
        $blog = Blog::with(['category', 'author', 'user'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // 2. "Bunları da Beğenebilirsiniz" bölümü için, aynı kategorideki diğer 4 haberi çek.
        $relatedBlogs = Blog::where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id) // Mevcut haberi hariç tut
            ->where('status', true)
            ->latest()
            ->limit(4)
            ->get();

        // 3. YAN BAR İÇERİĞİ (YENİ KOD)
        // a) Tüm kategorileri, aktif haber sayılarıyla birlikte çek.
        $sidebarCategories = Category::where('status', true)
            ->where('type', 'blog')
            ->withCount(['blogs' => function ($query) {
                $query->where('status', true);
            }])
            ->orderBy('order', 'asc')
            ->get();

        // b) Yan barda gösterilecek yazarları çek.
        $sidebarAuthors = Author::where('status', true)
            ->orderBy('order', 'asc')
            ->limit(5)
            ->get();

        // 4. Tüm verileri view'a gönder.
        return view('frontend.pages.blog_detail', compact(
            'blog',
            'relatedBlogs',
            'sidebarCategories',
            'sidebarAuthors'
        ));
    }
}
