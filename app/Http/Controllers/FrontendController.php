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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\TocService;

class FrontendController extends Controller
{
    public function __construct()
    {
        // GÜNCELLENDİ: Sadece yayınlanmış (tarihi gelmiş ve aktif) yazıları çek
        // created_at yerine published_at'e göre sıralamak daha doğrudur.
        $latestPosts = Blog::published()->orderBy('published_at', 'desc')->take(3)->get();
        View::share('latestPosts', $latestPosts);

        $settings = Setting::pluck('value', 'key')->all();
        View::share('settings', $settings);
    }

    public function index()
    {
        $slides = Slide::where('status', true)->orderBy('order', 'asc')->get();
        $projects = Project::where('status', true)->orderBy('order', 'asc')->take(3)->get();

        return view('frontend.pages.home', [
            'slides' => $slides,
            'projects' => $projects,
        ]);
    }

    public function about()
    {
        $aboutData = About::where('status', true)->first();
        $services = Service::where('status', true)->orderBy('order', 'asc')->get();

        return view('frontend.pages.about', [
            'about' => $aboutData,
            'services' => $services,
        ]);
    }

    public function servicesIndex()
    {
        $services = Service::where('status', true)->orderBy('order', 'asc')->get();
        return view('frontend.pages.services.index', compact('services'));
    }

    public function serviceDetail($slug) 
    {
        $service = Service::where('slug', $slug)->where('status', true)->firstOrFail(); 
        return view('frontend.pages.services.detail', compact('service'));
    }

    public function projectsIndex() 
    {
        $projects = Project::where('status', true)->orderBy('order', 'asc')->get(); 
        return view('frontend.pages.projects.index', compact('projects')); 
    }

    public function projectDetail($slug) 
    {
        $project = Project::with('gallery.items')
                          ->where('slug', $slug)
                          ->where('status', true)
                          ->firstOrFail(); 

        $otherProjects = Project::where('status', true)
                                ->where('id', '!=', $project->id)
                                ->inRandomOrder()
                                ->take(5)
                                ->get();

        return view('frontend.pages.projects.detail', compact('project', 'otherProjects')); 
    }

    // --- BLOG BÖLÜMÜ GÜNCELLENDİ ---

    public function blogIndex()
    {
        // GÜNCELLENDİ: published() scope kullanıldı ve published_at'e göre sıralandı
        $posts = Blog::published()->orderBy('published_at', 'desc')->paginate(10); 

        // GÜNCELLENDİ: published() scope kullanıldı
        $latestPostsSidebar = Blog::published()->orderBy('published_at', 'desc')->take(5)->get();

        return view('frontend.pages.blogs.index', compact('posts', 'latestPostsSidebar'));
    }

   public function blogDetail($slug)
    {
        // 1. Blog yazısını bul (Zaten yapmıştık)
        $post = Blog::published()->where('slug', $slug)->firstOrFail();

        // 2. İçindekiler tablosunu oluştur
        // TocService'i manuel çağırıyoruz (Dependency Injection da yapılabilir ama bu daha pratik)
        $tocService = new TocService();
        $tocData = $tocService->generate($post->content);

        // 3. İşlenmiş (ID eklenmiş) içeriği post objesine geçici olarak ata
        // Veritabanını değiştirmiyoruz, sadece o anlık gösterimi değiştiriyoruz.
        $post->content = $tocData['processed_content'];
        $tocList = $tocData['toc']; // View'a göndereceğimiz liste

        // ... (Önceki/Sonraki post kodları aynen kalıyor) ...
        $previousPost = Blog::published()
                            ->where('published_at', '<', $post->published_at)
                            ->orderBy('published_at', 'desc')
                            ->first();

        $nextPost = Blog::published()
                        ->where('published_at', '>', $post->published_at)
                        ->orderBy('published_at', 'asc')
                        ->first();

        $suggestedPosts = Blog::published()
                            ->where('id', '!=', $post->id)
                            ->when($previousPost, fn($q) => $q->where('id', '!=', $previousPost->id))
                            ->when($nextPost, fn($q) => $q->where('id', '!=', $nextPost->id))
                            ->inRandomOrder()
                            ->take(3)
                            ->get();

        // View'a $tocList değişkenini de gönderiyoruz
        return view('frontend.pages.blogs.detail', compact('post', 'previousPost', 'nextPost', 'suggestedPosts', 'tocList'));
    }

    // --- İLETİŞİM ---

    public function contact()
    {
        return view('frontend.pages.contact'); 
    }

    public function handleContactForm(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            $adminEmail = Setting::where('key', 'email')->value('value');

            Mail::raw("Gönderen: {$validated['username']} ({$validated['email']})\n\nMesaj:\n{$validated['message']}", function ($message) use ($validated, $adminEmail) {
                $message->to($adminEmail)
                        ->subject('Web Sitesi İletişim Formu Mesajı');
                $message->from($validated['email'], $validated['username']);
            });

            return redirect()->route('frontend.contact')
                             ->with('success', 'Mesajınız başarıyla gönderildi. Teşekkür ederiz!');

        } catch (\Exception $e) {
             Log::error('İletişim formu e-posta gönderme hatası: ' . $e->getMessage());
            return redirect()->route('frontend.contact')
                             ->with('error', 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
}