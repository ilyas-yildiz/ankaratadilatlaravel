<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\ImageService;
use Illuminate\Support\Facades\Validator; // <-- BU IMPORT ÇOK ÖNEMLİ!

class ReferenceController extends Controller
{
    /**
     * Görsellerin kaydedileceği ana dizin (Storage/public altındaki)
     */
    protected $basePath = 'reference-images';

    /**
     * ImageService tarafından oluşturulacak boyutlar
     */
    protected $sizes = [
        '900x600', 
        '600x400',
        '200x133',
    ];

    public function index()
    {
        $items = Reference::orderBy('order')->get();
        return view('admin.references.index', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        // Doğrulama
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Doğrulama hatası varsa JSON döndür (forms-handler.js için)
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); 
        }
        
        $fileName = null;
        
        // ImageService ile Görsel Yükleme
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $imageService->saveImage($file, $this->basePath, $this->sizes); 

            if (!$fileName) {
                 // Görsel yükleme hatası olursa JSON döndür
                return response()->json(['success' => false, 'message' => 'Görsel yüklenirken bir hata oluştu!'], 500);
            }
        }

        // Veritabanına Kayıt
        Reference::create([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'website_url' => $request->website_url,
            'image_url' => $fileName,
            'order' => Reference::max('order') + 1,
            'status' => true,
        ]);

        // Başarılı durumda JSON döndür (forms-handler.js bunu bekliyor)
        return response()->json(['success' => true, 'message' => 'Referans başarıyla eklendi!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reference $reference, ImageService $imageService)
    {
        // Doğrulama
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Doğrulama hatası varsa JSON döndür (forms-handler.js için)
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); 
        }

        // Görsel Güncelleme İşlemi
        if ($request->hasFile('image')) {
            
            // a) Eski görseli SİLME
            if ($reference->image_url) {
                $imageService->deleteImages($reference->image_url, $this->basePath, $this->sizes);
            }
            
            // b) Yeni görseli YÜKLEME
            $file = $request->file('image');
            $fileName = $imageService->saveImage($file, $this->basePath, $this->sizes); 

            if ($fileName) {
                $reference->image_url = $fileName;
            } else {
                // Görsel yüklemede hata olursa JSON döndür
                return response()->json(['success' => false, 'message' => 'Yeni görsel yüklenirken bir hata oluştu!'], 500);
            }
        }
        
        // Veritabanı Kaydını Güncelleme
        $reference->name = $request->name;
        $reference->title = $request->title;
        $reference->description = $request->description;
        $reference->website_url = $request->website_url;
        
        $reference->save();

        // Başarılı durumda JSON döndür
        return response()->json([
            'success' => true, 
            'message' => 'Referans başarıyla güncellendi!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reference $reference, ImageService $imageService)
    {
        // Görseli ImageService ile silme
        if ($reference->image_url) {
            $imageService->deleteImages($reference->image_url, $this->basePath, $this->sizes);
        }
        
        $reference->delete();

        // Başarılı durumda JSON döndür
        return response()->json(['success' => true, 'message' => 'Referans başarıyla silindi!']);
    }
}