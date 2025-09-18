<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category; // Category modelini kullanacağımızı belirtiyoruz
use App\Models\Setting;
use Illuminate\Support\Facades\Schema; // EKSİK OLAN VE EKLENEN SATIR


class ViewServiceProvider extends ServiceProvider
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
        // Bu, bir View Composer tanımlar.
        // Bu kod, 'frontend.partials._header' view'ı her yüklendiğinde otomatik olarak çalışır.
        View::composer('frontend.*', function ($view) {
            // Sadece aktif olan ve 'blog' tipindeki kategorileri çek,
            // 'order' sütununa göre sırala.
            $categories = Category::where('status', 1)
                ->where('type', 'blog')
                ->orderBy('order', 'asc')
                ->get();

            // Çektiğimiz kategorileri, '$categories' adıyla view'a gönder.
            $view->with('categories', $categories);
        });

        View::composer('frontend.*', function ($view) {
            // Migration'lar çalıştırılmadan önce hata vermemesi için bir önlem
            if (Schema::hasTable('settings')) {
                $settings = Setting::all()->pluck('value', 'key')->all();
                $view->with('settings', $settings);
            } else {
                // Eğer settings tablosu henüz yoksa, boş bir dizi gönderir.
                $view->with('settings', []);
            }
        });
    }
}
