<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Ayarlar formunu gösterir.
     */
    public function index()
    {
        // Tüm ayarları veritabanından çekip, 'key' sütununu anahtar olarak kullanarak
        // kolay erişilebilir bir diziye dönüştürüyoruz.
        $settings = Setting::all()->pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Ayarları günceller.
     */
    public function update(Request $request)
    {
        // Formdan gelen tüm verileri alıyoruz.
        $settings = $request->except('_token');

        // Her bir ayarı döngüye alıp veritabanında güncelliyoruz veya oluşturuyoruz.
        // updateOrCreate metodu, ilgili 'key' ile bir kayıt bulursa günceller, bulamazsa yenisini oluşturur.
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
