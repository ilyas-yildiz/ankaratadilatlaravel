<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class DirectoryTree
{
    /**
     * Verilen klasörün içindeki dosya/klasör yapısını ağaç formatında döndürür.
     *
     * @param string $path
     * @param string $prefix
     * @return string
     */
    public static function getTree(string $path, string $prefix = ''): string
    {
        if (!File::exists($path)) {
            return "Klasör bulunamadı: " . $path;
        }

        $output = basename($path) . PHP_EOL;

        $items = array_merge(File::directories($path), File::files($path));
        $total = count($items);

        foreach ($items as $index => $item) {
            $isLast = $index === $total - 1;
            $connector = $isLast ? '└── ' : '├── ';
            $subPrefix = $isLast ? '    ' : '│   ';

            if (is_dir($item)) {
                $output .= $prefix . $connector . basename($item) . PHP_EOL;
                $output .= self::getTree($item, $prefix . $subPrefix);
            } else {
                $output .= $prefix . $connector . basename($item) . PHP_EOL;
            }
        }

        return $output;
    }
}
