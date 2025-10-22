<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Service;
use App\Models\Blog;
use App\Models\Project;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View; // View::share için (opsiyonel ama kullanışlı)

class FrontendController extends Controller
{
    // Anasayfa metodu (varsa kalsın)
    public function index()
    {
        $slides = Slide::where('status', true)->orderBy('order', 'asc')->get();
        // Anasayfada gösterilecek aktif projeleri çek (örneğin son 3 tane, sıralamaya göre)
        $projects = Project::where('status', true)->orderBy('order', 'asc')->take(3)->get();

        // Footer için son yazılar (varsa)
        $latestPosts = Blog::where('status', true)->latest()->take(3)->get();

        // Verileri view'a gönder
        return view('frontend.pages.home', [ // View yolunu 'pages' altına aldık
            'slides' => $slides,
            'projects' => $projects,
            'latestPosts' => $latestPosts,
        ]);
    }

    // YENİ METOT: Hakkımızda sayfası
    public function about()
    {
        // Veritabanından Hakkımızda içeriğini çek (genellikle ilk veya tek kayıt)
        $aboutData = About::where('status', true)->first(); // Aktif olan ilk kaydı al

        // "Neler Yapıyoruz?" bölümü için aktif hizmetleri çek (sıralı)
        $services = Service::where('status', true)->orderBy('order', 'asc')->get();

        // Footer için son 3 blog yazısını çek (Blog modelini ve rotasını varsayarak)
        $latestPosts = Blog::where('status', true)->latest()->take(3)->get();

        // View::share ile $latestPosts'u tüm view'larda kullanılabilir yapabiliriz
        // veya sadece bu view'a gönderebiliriz. Şimdilik sadece gönderelim.
        // View::share('latestPosts', $latestPosts); 

        // Verileri view'a gönder
        return view('frontend.pages.about', [
            'about' => $aboutData, // View içinde $about değişkeniyle erişilecek
            'services' => $services, // View içinde $services değişkeniyle erişilecek
            'latestPosts' => $latestPosts, // Footer partial'ı için
        ]);
    }

    // Blog listesi metodu (varsa kalsın)
    public function blogIndex()
    {
        $posts = Blog::where('status', true)->latest()->paginate(10); // Örnek
        $latestPosts = Blog::where('status', true)->latest()->take(3)->get();
        return view('frontend.pages.blog.index', compact('posts', 'latestPosts'));
    }

    // Blog detay metodu (varsa kalsın)
    public function blogDetail($slug)
    {
        $post = Blog::where('slug', $slug)->where('status', true)->firstOrFail();
        $latestPosts = Blog::where('status', true)->latest()->take(3)->get();
        return view('frontend.pages.blog.detail', compact('post', 'latestPosts'));
    }

    // Diğer frontend metodları... (hizmetler, projeler, iletişim vb.)
    public function services()
    {
        // Varsayılan: Hizmetler view'ı pages altında
        return view('frontend.pages.services');
    }
    public function projects()
    {
        // Varsayılan: Projeler view'ı pages altında
        return view('frontend.pages.projects');
    }
    public function contact()
    {
        // Varsayılan: İletişim view'ı pages altında
        return view('frontend.pages.contact');
    }


}