<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'company_name' => 'Memur Hukuk',
            'address' => 'Lizbon Cd. 15/8',
            'phone' => '+90 312 232 2645',
            'fax' => '+90 312 232 2646',
            'email' => 'bilgi@memurhukuk.com',
            'social_facebook' => 'https://facebook.com/memurhukuk',
            'social_twitter' => 'https://x.com/memurhukuk',
            'social_instagram' => 'https://instagram.com/memurhukuk',
            'google_maps' => '',
            'directions_info' => '',
            'homepage_meta_description' => 'MemurHukuk.com, memurlar, askerler ve polisler için güncel hukuki haberler, mahkeme kararları, mevzuat değişiklikleri ve uzman yorumlarını sunar. Kamu personeline özel hukuk gündemini takip edin.',
            'homepage_meta_keywords' => 'memur hukuk, asker hukuk, polis hukuk, kamu personeli hukuku, hukuki haberler, güncel mevzuat, mahkeme kararları, kamu hukuku, memur haberleri, asker haberleri, polis haberleri',
            'footer_aboutus' => 'MemurHukuk.com; memurlar, askerler ve polisler başta olmak üzere tüm kamu personeline yönelik güncel hukuki gelişmeleri, mevzuat değişikliklerini ve yargı kararlarını tarafsız bir bakış açısıyla aktarmak amacıyla kurulmuştur.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
