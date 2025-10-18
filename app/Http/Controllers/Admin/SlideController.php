<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide; // <-- Bu import OLMALI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\ImageService; // Servisi import edin


class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Görsellerin kaydedileceği ana dizin (Storage/public altındaki)
    protected $basePath = 'slide-images';

    // ImageService tarafından oluşturulacak boyutlar
    protected $sizes = [
        '1920x600', // Ana slayt boyutu
        '600x400',  // Admin listesi/önizleme için küçük boyut
    ];

public function index()
    {
        // dd('TEST 1 BAŞARILI: Controller basariyla calisiyor.'); // Bu satırı SİLİN.
        
        $items = Slide::orderBy('order')->get(); // <-- Bu satır hata veriyor olabilir.
        
        // Eğer bu satıra ulaşırsak, View hatasız yüklenmeli.
        return view('admin.slides.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request, ImageService $imageService) // <-- ImageService'i ENJEKTE EDİN
{
    // 1. Doğrulama (Görsel artık zorunlu)
    $request->validate([
        'title' => 'nullable|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'link' => 'nullable|url|max:255',
        'button_text' => 'nullable|string|max:50',
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072', // 'image' alanı
    ]);
    
    $fileName = null;
    
    // 2. ImageService ile Görsel Yükleme
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        
        // ImageService'in saveImage metodunu kullan
        $fileName = $imageService->saveImage($file, $this->basePath, $this->sizes); 

        if (!$fileName) {
            return redirect()->route('admin.slides.index')->with('error', 'Görsel yüklenirken bir hata oluştu!')->withInput();
        }
    }

    // 3. Veritabanına Kayıt
    $slide = Slide::create([
        'title' => $request->title,
        'subtitle' => $request->subtitle,
        'link' => $request->link,
        'button_text' => $request->button_text,
        'image_url' => $fileName,
        'order' => Slide::max('order') + 1,
        'status' => true, // <-- Otomatik olarak AKTİF (true) yapıldı.
    ]);

    return redirect()->route('admin.slides.index')->with('success', 'Slayt başarıyla eklendi!');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Slide $slide, ImageService $imageService)
{
    // 1. Doğrulama (Görsel alanı artık 'nullable' (isteğe bağlı) olmalıdır)
    $request->validate([
        'title' => 'nullable|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'link' => 'nullable|url|max:255',
        'button_text' => 'nullable|string|max:50',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072', // Görsel opsiyonel

    ]);

    // 2. Görsel Güncelleme İşlemi
    if ($request->hasFile('image')) {
        
        // a) Eski görseli SİLME (ImageService ile)
        if ($slide->image_url) {
            // base_path ve sizes değişkenlerini SlideController sınıfında tanımlamıştık.
            $imageService->deleteImages($slide->image_url, $this->basePath, $this->sizes);
        }
        
        // b) Yeni görseli YÜKLEME (ImageService ile)
        $file = $request->file('image');
        $fileName = $imageService->saveImage($file, $this->basePath, $this->sizes); 

        if ($fileName) {
            // Yeni dosya adını veritabanına kaydetmek için slayt objesine atama
            $slide->image_url = $fileName;
        } else {
            // Görsel yüklemede hata varsa geri dön
            return redirect()->back()->with('error', 'Yeni görsel yüklenirken bir hata oluştu!');
        }
    }
    
    // 3. Veritabanı Kaydını Güncelleme
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->link = $request->link;
    $slide->button_text = $request->button_text;
    
    $slide->save();

    return redirect()->route('admin.slides.index')->with('success', 'Slayt başarıyla güncellendi!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slide $slide, ImageService $imageService) // <-- ImageService'i ENJEKTE EDİN
{
    // Görseli ImageService ile silme
    if ($slide->image_url) {
        $imageService->deleteImages($slide->image_url, $this->basePath, $this->sizes);
    }
    
    $slide->delete();

    return redirect()->route('admin.slides.index')->with('success', 'Slayt başarıyla silindi!');
}
}
