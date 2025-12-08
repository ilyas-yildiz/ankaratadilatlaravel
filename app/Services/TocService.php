<?php

namespace App\Services;

use DOMDocument;
use Illuminate\Support\Str;

class TocService
{
    /**
     * HTML içeriğini tarar, başlıklara ID ekler ve bir İçindekiler listesi oluşturur.
     *
     * @param string|null $content HTML İçeriği
     * @return array ['processed_content' => string, 'toc' => array]
     */
    public function generate(?string $content): array
    {
        if (empty($content)) {
            return ['processed_content' => '', 'toc' => []];
        }

        // Türkçe karakter sorununu çözmek için encoding dönüşümü
        // DOMDocument bazen UTF-8 karakterleri bozabilir, bu önlem şarttır.
        $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');

        $dom = new DOMDocument();
        // Hataları gizle (HTML5 etiketlerinde uyarı verebilir, önemsizdir)
        libxml_use_internal_errors(true);
        
        // HTML'i yükle (Wrapper ekleyerek yapısını koruyoruz)
        $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        // H2, H3, H4, H5, H6 etiketlerini bul
        $headings = $xpath->query('//h2|//h3|//h4|//h5|//h6');

        $toc = [];
        $usedSlugs = [];

        foreach ($headings as $heading) {
            $text = $heading->textContent;
            
            // Başlık boşsa atla
            if (empty(trim($text))) continue;

            // Slug oluştur (SEO dostu URL parçası)
            $slug = Str::slug($text);

            // Eğer aynı başlık birden fazla varsa sonuna numara ekle (örn: giris-1, giris-2)
            if (in_array($slug, $usedSlugs)) {
                $count = 1;
                while (in_array($slug . '-' . $count, $usedSlugs)) {
                    $count++;
                }
                $slug = $slug . '-' . $count;
            }
            $usedSlugs[] = $slug;

            // HTML etiketine ID'yi ekle (Frontend'de linkin gideceği yer)
            $heading->setAttribute('id', $slug);

            // TOC dizisine ekle
            $toc[] = [
                'text' => $text,
                'slug' => '#' . $slug,
                'level' => (int) substr($heading->tagName, 1) // h2 -> 2, h3 -> 3
            ];
        }

        // İşlenmiş HTML'i geri döndür
        $processedContent = $dom->saveHTML();

        return [
            'processed_content' => $processedContent,
            'toc' => $toc
        ];
    }
}