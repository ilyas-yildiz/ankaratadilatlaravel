<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Service;
use App\Models\Blog;
use App\Models\Project;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View; // View::share için (opsiyonel ama kullanışlı)
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function __construct()
    {
        // Footer için son yazıları tüm frontend view'larına gönderelim
        $latestPosts = Blog::where('status', true)->latest()->take(3)->get();
        View::share('latestPosts', $latestPosts);
    }

    // Anasayfa metodu (varsa kalsın)
    public function index()
    {
        $slides = Slide::where('status', true)->orderBy('order', 'asc')->get();
        // Anasayfada gösterilecek aktif projeleri çek (örneğin son 3 tane, sıralamaya göre)
        $projects = Project::where('status', true)->orderBy('order', 'asc')->take(3)->get();

        // Verileri view'a gönder
        return view('frontend.pages.home', [ // View yolunu 'pages' altına aldık
            'slides' => $slides,
            'projects' => $projects,
        ]);
    }

    // YENİ METOT: Hakkımızda sayfası
    public function about()
    {
        // Veritabanından Hakkımızda içeriğini çek (genellikle ilk veya tek kayıt)
        $aboutData = About::where('status', true)->first(); // Aktif olan ilk kaydı al

        // "Neler Yapıyoruz?" bölümü için aktif hizmetleri çek (sıralı)
        $services = Service::where('status', true)->orderBy('order', 'asc')->get();

        // View::share ile $latestPosts'u tüm view'larda kullanılabilir yapabiliriz
        // veya sadece bu view'a gönderebiliriz. Şimdilik sadece gönderelim.
        // View::share('latestPosts', $latestPosts); 

        // Verileri view'a gönder
        return view('frontend.pages.about', [
            'about' => $aboutData, // View içinde $about değişkeniyle erişilecek
            'services' => $services, // View içinde $services değişkeniyle erişilecek
        
        ]);
    }

    // YENİ METOT: Hizmetler Listeleme Sayfası
    public function servicesIndex()
    {
        // Aktif hizmetleri sıralı olarak çek
        $services = Service::where('status', true)->orderBy('order', 'asc')->get();

        // latestPosts share ediliyor
        return view('frontend.pages.services.index', compact('services')); // Yeni view yolu
    }

    // YENİ METOT: Hizmet Detay Sayfası
    // Slugify edilmiş başlığı URL'de kullanacağız
    public function serviceDetail($slug) 
    {
        // Slug'a göre aktif hizmeti bul, bulunamazsa 404 döndür
        $service = Service::where('slug', $slug)->where('status', true)->firstOrFail(); 

        // latestPosts share ediliyor
        return view('frontend.pages.services.detail', compact('service')); // Yeni view yolu
    }

    // Blog listesi metodu (varsa kalsın)
    public function blogIndex()
    {
        $posts = Blog::where('status', true)->latest()->paginate(10); // Örnek
        return view('frontend.pages.blog.index', compact('posts'));
    }

    // Blog detay metodu (varsa kalsın)
    public function blogDetail($slug)
    {
        $post = Blog::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('frontend.pages.blog.detail', compact('post'));
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