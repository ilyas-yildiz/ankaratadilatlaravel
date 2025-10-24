<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Service;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Project;
use App\Models\Slide;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response; // YENİ EKLEME: Response sınıfı için

// Sitemap için yeni eklenenler
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class FrontendController extends Controller
{
    public function __construct()
    {
        // Footer için son yazıları tüm frontend view'larına gönderelim
        $latestPosts = Blog::where('status', true)->latest()->take(3)->get();
        View::share('latestPosts', $latestPosts);

        // YENİ: Ayarları da tüm view'larla paylaşabiliriz (opsiyonel)
        // Ayarları tek seferde çekip bir diziye atalım (key => value)
        $settings = Setting::pluck('value', 'key')->all();
        View::share('settings', $settings);
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
        // Aktif blog yazılarını en yeniden eskiye doğru sayfalı olarak çek
        // Örneğin, sayfa başına 10 yazı gösterelim
        $posts = Blog::where('status', true)->latest()->paginate(10); 

        // Sidebar için son yazılar (footer'dakiyle aynı olabilir)
        $latestPostsSidebar = Blog::where('status', true)->latest()->take(5)->get(); // Sidebar için 5 tane alalım

        return view('frontend.pages.blogs.index', compact('posts', 'latestPostsSidebar'/*, 'categories'*/));
    }

    // GÜNCELLENDİ: Blog Detay Sayfası
    public function blogDetail($slug)
    {
        // Slug'a göre aktif yazıyı bul
        $post = Blog::where('slug', $slug)->where('status', true)->firstOrFail();

        // Önceki yazı (mevcut yazıdan daha eski olan ilk yazı)
        $previousPost = Blog::where('status', true)
                            ->where('created_at', '<', $post->created_at)
                            ->orderBy('created_at', 'desc')
                            ->first();

        // Sonraki yazı (mevcut yazıdan daha yeni olan ilk yazı)
        $nextPost = Blog::where('status', true)
                        ->where('created_at', '>', $post->created_at)
                        ->orderBy('created_at', 'asc')
                        ->first();

        // Önerilen yazılar (mevcut yazı ve önceki/sonraki hariç, rastgele 3 tane)
        $suggestedPosts = Blog::where('status', true)
                            ->where('id', '!=', $post->id) // Mevcut yazı hariç
                            ->when($previousPost, fn($q) => $q->where('id', '!=', $previousPost->id)) // Önceki hariç (varsa)
                            ->when($nextPost, fn($q) => $q->where('id', '!=', $nextPost->id)) // Sonraki hariç (varsa)
                            ->inRandomOrder()
                            ->take(3)
                            ->get();


        return view('frontend.pages.blogs.detail', compact('post', 'previousPost', 'nextPost', 'suggestedPosts'));
    }

    public function projectsIndex() 
    {
        // Tüm aktif projeleri sıralı olarak çek
        $projects = Project::where('status', true)->orderBy('order', 'asc')->get(); 

        return view('frontend.pages.projects.index', compact('projects'/*, 'projectTypes'*/)); 
    }

    // YENİ METOT: Proje Detay Sayfası
    public function projectDetail($slug) 
    {
        // Slug'a göre aktif projeyi bul, ilişkili galeriyi de yükle ('with')
        $project = Project::with('gallery.items') // Galeri ve içindeki item'ları eager load et
                         ->where('slug', $slug)
                         ->where('status', true)
                         ->firstOrFail(); 

        // Benzer (diğer) projeleri çek (şimdilik rastgele 5 tane, mevcut proje hariç)
        $otherProjects = Project::where('status', true)
                               ->where('id', '!=', $project->id) // Mevcut projeyi hariç tut
                               ->inRandomOrder() // Rastgele sırala
                               ->take(5) // İlk 5 tanesini al
                               ->get();

        return view('frontend.pages.projects.detail', compact('project', 'otherProjects')); 
    }
    public function contact()
    {
        return view('frontend.pages.contact'); 
    }

    // YENİ: İletişim Formunu İşleme Metodu
    public function handleContactForm(Request $request)
    {
        // Formdan gelen veriyi doğrula
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // E-posta gönderme mantığı buraya gelecek
        try {
            $adminEmail = Setting::where('key', 'email')->value('value'); 

            \Illuminate\Support\Facades\Mail::raw("Gönderen: {$validated['username']} ({$validated['email']})\n\nMesaj:\n{$validated['message']}", function ($message) use ($validated, $adminEmail) {
                $message->to($adminEmail)
                        ->subject('Web Sitesi İletişim Formu Mesajı');
                $message->from($validated['email'], $validated['username']);
            });

            // Başarılı olursa geri yönlendir ve başarı mesajı göster
            return redirect()->route('frontend.contact')
                             ->with('success', 'Mesajınız başarıyla gönderildi. Teşekkür ederiz!');

        } catch (\Exception $e) {
             \Illuminate\Support\Facades\Log::error('İletişim formu e-posta gönderme hatası: ' . $e->getMessage()); 
            return redirect()->route('frontend.contact')
                             ->with('error', 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    // -----------------------------------------------------------
    // YENİ EKLEME: GERÇEK ZAMANLI SITEMAP METODU
    // -----------------------------------------------------------
    /**
     * Sitenin sitemap.xml dosyasını anlık olarak (on-demand) oluşturur ve XML formatında döndürür.
     * Bu yöntem, Cron Job ihtiyacını ortadan kaldırır.
     *
     * @return \Illuminate\Http\Response
     */
   public function generateSitemap()
    {
        // Statik ve dinamik rotaları barındıracak Sitemap nesnesi oluşturuluyor.
        $sitemap = Sitemap::create();
        
        // --- 1. STATİK ROTALAR ---
        
        // Ana Sayfa (En yüksek öncelik)
        $sitemap->add(
            Url::create('/')
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // Hakkımızda ve İletişim
        $sitemap->add(Url::create('/hakkimizda')->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)->setPriority(0.8));
        $sitemap->add(Url::create('/iletisim')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.7));
        
        // Liste Sayfaları
        $sitemap->add(Url::create('/hizmetlerimiz')->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.8));
        $sitemap->add(Url::create('/projelerimiz')->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.8));
        $sitemap->add(Url::create('/blog')->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.9));
        
        // --- 2. DİNAMİK ROTALAR (Veritabanı) ---

        // Blog Yazıları (Blog) - En önemli içerikler
        Blog::where('status', true)->get()->each(function (Blog $blog) use ($sitemap) {
            $sitemap->add(
                Url::create("/blog/{$blog->slug}")
                    ->setLastModificationDate($blog->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
            );
        });

        // Hizmet Detayları (Service)
        Service::where('status', true)->get()->each(function (Service $service) use ($sitemap) {
            $sitemap->add(
                Url::create("/hizmetlerimiz/{$service->slug}")
                    ->setLastModificationDate($service->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        });

        // Proje Detayları (Project)
        Project::where('status', true)->get()->each(function (Project $project) use ($sitemap) {
            $sitemap->add(
                Url::create("/projelerimiz/{$project->slug}")
                    ->setLastModificationDate($project->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        });
        
        // ÖNEMLİ DÜZELTME: Sitemap içeriğini Response::make ile manuel olarak döndürürken
        // Content-Type başlığını XML olarak ayarla.
        return Response::make(
            $sitemap->render(), 
            200, 
            ['Content-Type' => 'application/xml']
        );
    }
}

