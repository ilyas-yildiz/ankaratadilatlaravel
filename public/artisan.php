<?php

// Basit bir güvenlik önlemi. Tarayıcıdan bu adrese giderken ?key=ŞİFRENİZ şeklinde gitmeniz gerekecek.
// Lütfen 'COK_GIZLI_SIFRE' kısmını kendinize özel, tahmin edilmesi zor bir şeyle değiştirin.
$secretKey = 'Termostat1?*!!!';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    die('Erisim Reddedildi.');
}

// İzin verilen komutların listesi. Güvenlik için sadece ihtiyacınız olanları ekleyin.
$allowedCommands = [
    'migrate',
    'config:cache',
    'config:clear',
    'route:cache',
    'route:clear',
    'view:clear',
    'cache:clear',
    'optimize',
    'optimize:clear',
    'storage:link', // Her ihtimale karşı
];

$command = isset($_GET['command']) ? $_GET['command'] : null;

if (!$command || !in_array($command, $allowedCommands)) {
    die('Gecersiz veya izin verilmeyen bir komut girdiniz.');
}

// Laravel'i başlat ve komutu çalıştır
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Komut çıktısını yakalamak için
ob_start();
$kernel->call($command);
$output = ob_get_clean();

// Çıktıyı ekrana bas
echo "<pre><strong>Komut Calistirildi: php artisan {$command}</strong>\n\n{$output}</pre>";

?>
