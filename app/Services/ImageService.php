<?php

namespace App\Services;

// use Intervention\Image\ImageManagerStatic as Image;  <-- Bu satırı sil
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class ImageService
{
    /**
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $basePath
     * @param  array  $sizes
     * @return string|null
     */
    public function saveImage($file, $basePath, array $sizes)
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        try {
            foreach ($sizes as $size) {
                [$width, $height] = explode('x', $size);

                $directory = "{$basePath}/{$size}";
                $path = "{$directory}/{$filename}";

                $manager = new \Intervention\Image\ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );

                // make() yerine read() metodunu kullanın
                $img = $manager->read($file)->cover($width, $height);

                Storage::disk('public')->put($path, $img->encode());
            }

            return $filename;
        } catch (Exception $e) {
            \Log::error('Görsel kaydetme hatası: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Görsel ve ilişkili boyutlarını siler.
     *
     * @param  string  $filename
     * @param  string  $basePath
     * @param  array  $sizes
     * @return void
     */
    public function deleteImages($filename, $basePath, array $sizes)
    {
        foreach ($sizes as $size) {
            // Görsel yolu artık 'public/' öneki olmadan tanımlanacak
            $path = "{$basePath}/{$size}/{$filename}";

            // Görseli 'public' diski üzerinden sil
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
