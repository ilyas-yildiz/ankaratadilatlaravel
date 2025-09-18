<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryItemController extends Controller
{
    /**
     * Store a new gallery item (image) for a given gallery.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Gallery $gallery)
    {
        try {
            // Validate the incoming file
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'success' => false,
                    'message' => 'Resim formatı veya boyutu geçersiz.',
                ], 422);
            }

            // Get the uploaded file
            $file = $request->file('file');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Store the file in the storage directory
            $path = Storage::disk('public')->putFileAs('galleries/' . $gallery->id, $file, $fileName);

            if ($path) {
                // Create a new gallery item record in the database
                $item = new GalleryItem();
                $item->gallery_id = $gallery->id;
                $item->image = $path;
                $item->status = true; // Set default status to active
                $item->save();

                return Response::json([
                    'success' => true,
                    'message' => 'Görsel başarıyla yüklendi.',
                    'item' => [
                        'id' => $item->id,
                        'image_url' => asset('storage/' . $item->image),
                        'status' => $item->status,
                    ]
                ]);
            }

            return Response::json([
                'success' => false,
                'message' => 'Dosya yüklenirken bir hata oluştu.',
            ], 500);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Bir sunucu hatası oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(GalleryItem $galleryItem)
    {
        try {
            // Kapak görseli kontrolü kaldırıldı.

            // Delete the file from storage
            if (Storage::disk('public')->exists($galleryItem->image)) {
                Storage::disk('public')->delete($galleryItem->image);
            }

            // Delete the database record
            $galleryItem->delete();

            return Response::json([
                'success' => true,
                'message' => 'Görsel başarıyla silindi.',
            ]);

        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Silme işlemi sırasında bir hata oluştu.',
            ], 500);
        }
    }
}
