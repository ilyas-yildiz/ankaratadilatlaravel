<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use Illuminate\Support\Facades\File;
// << BU SATIRI EKLE
class SlideController extends Controller
{
    /**
     * Tüm slaytları listeleyen sayfayı gösterir.
     * Bu genellikle admin panelindeki "Slaytlar" ana sayfasıdır.
     */
    public function index()
    {
        // Slaytları 'order' sütununa göre sıralayarak çekiyoruz.
        $slides = Slide::orderBy('order', 'asc')->get();
        return view('admin.slides.index', compact('slides'));
    }
    /**
     * Yeni bir slayt ekleme formunu gösteren sayfayı açar.
     */
    public function create()
    {
        return view('admin.slides.create');
    }
    /**
     * 'create' formundan gelen bilgileri veritabanına kaydeder.
     */
    public function store(Request $request)
    {
        // 1. Gelen veriyi doğrula
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'new_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $imagePath = null;
        // 2. Görseli Yükle (public/storage içine)
        if ($request->hasFile('new_image')) {
            $image = $request->file('new_image');
            // Çakışmaları önlemek için dosya adını zaman damgası ile yeniden adlandırıyoruz
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Dosyayı public/storage/slides klasörüne taşıyoruz
            $image->move(public_path('storage/slides'), $imageName);
            // Veritabanına kaydedilecek yolu oluşturuyoruz
            $imagePath = 'slides/' . $imageName;
        }
        // 3. Veritabanına kaydedilecek verileri hazırla
        $dataToSave = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'link' => $validated['link'],
            'image' => $imagePath,
            'status' => $request->has('status'),
        ];
        // 4. Veritabanına kaydet
        Slide::create($dataToSave);
        // store metodunun sonu...
// 5. Başarılı cevabı döndür
        if ($request->wantsJson()) {
            // Eğer istek AJAX ile geldiyse, JSON cevabı döndür
            return response()->json([
                'success' => true,
                'message' => 'Slayt başarıyla eklendi!'
            ]);
        }
// Eğer istek normal bir form gönderimi ise (JavaScript kapalıysa vb.), yönlendirme yap
        return redirect()->route('admin.slides.index')->with('success', 'Slayt başarıyla eklendi!');
    }
    /**
     * Belirli bir slaytın detaylarını gösterir. (Genellikle admin panellerinde çok kullanılmaz)
     */
    public function show($id)
    {
        //
    }
    /**
     * Belirli bir slaytı düzenlemek için formu gösterir.
     */
    public function edit(Slide $slide)
    {
        $data = $slide->toArray();
        unset($data['image']);
        $data['image_full_url'] = $slide->image_full_url;
        return response()->json($data);
    }
    /**
     * 'edit' formundan gelen güncel bilgileri veritabanına kaydeder.
     */
    public function update(Request $request, Slide $slide)
    {
        // 1. Gelen veriyi doğrula (resim zorunlu değil)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'new_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        // 2. Yeni görsel yüklendiyse eskisini sil, yenisini kaydet
        if ($request->hasFile('new_image')) {
            // Eski resmi sil
            if (File::exists(public_path('storage/' . $slide->image))) {
                File::delete(public_path('storage/' . $slide->image));
            }
            // Yeni resmi yükle
            $image     = $request->file('new_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/slides'), $imageName);
            $validated['image'] = 'slides/' . $imageName;
        }
        // 3. Durum (status) verisini işle
        $validated['status'] = $request->has('status');
// 4. Veritabanında güncelle
        $slide->update($validated);
// 5. Başarılı cevabı döndür
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Slayt başarıyla güncellendi!'
            ]);
        }
        return redirect()->route('admin.slides.index')->with('success', 'Slayt başarıyla güncellendi!');
    }
    /**
     * Belirli bir slaytı veritabanından siler.
     */
    public function destroy(Slide $slide)
    {
        $slide->delete(); // Model'deki booted metodu görseli silecek
        // Standart form gönderimi olduğu için sayfaya yönlendirme yapıyoruz
        return redirect()->route('admin.slides.index')->with('success', 'Slayt başarıyla silindi.');
    }
}
