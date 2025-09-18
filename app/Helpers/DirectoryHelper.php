<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class DirectoryHelper
{
    /**
     * Verilen klasörün içindeki tüm dosya ve klasörleri hiyerarşik şekilde döndürür.
     *
     * @param string $path
     * @return array
     */
    public static function getStructure($path)
    {
        $result = [];

        if (!File::exists($path)) {
            return ["error" => "Klasör bulunamadı: " . $path];
        }

        $files = File::directories($path);
        $allFiles = File::files($path);

        // Alt klasörleri tara
        foreach ($files as $dir) {
            $result[] = [
                'type' => 'directory',
                'name' => basename($dir),
                'children' => self::getStructure($dir)
            ];
        }

        // Dosyaları ekle
        foreach ($allFiles as $file) {
            $result[] = [
                'type' => 'file',
                'name' => $file->getFilename()
            ];
        }

        return $result;
    }
}
