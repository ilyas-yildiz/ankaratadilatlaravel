<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // YENİ EKLENEN KISIM BAŞLANGICI
        // Carbon'un çeviriler için kullanacağı yerel ayarı Türkçe yap
        Carbon::setLocale(config('app.locale')); 

        // (Opsiyonel ama önerilir) PHP'nin kendi zaman fonksiyonları için de yerel ayarı ayarla
        // Bu, sunucu ortamına bağlı olarak farklı formatlarda gerekebilir: 'tr_TR', 'tr_TR.utf8', 'tr'
        // Genellikle 'tr_TR.utf8' çoğu Linux sisteminde çalışır.
        setlocale(LC_TIME, 'tr_TR.utf8'); 
        // YENİ EKLENEN KISIM SONU
    }
}
