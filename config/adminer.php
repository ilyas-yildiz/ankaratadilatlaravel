<?php

return [
    'enabled' => env('ADMINER_ENABLED', true),

    'route' => [
        'prefix'    => 'adminer',
        'name'      => 'adminer',
        
        // BU, OLMASI GEREKEN TEK DOĞRU AYARDIR.
        // Sadece 'web' middleware'ını kullanarak tüm yönlendirme ve
        // "class not found" hatalarını ortadan kaldırıyoruz.
        'middleware' => ['web'],
    ],

    'db' => [
        'default'   => 'mysql',
        'host'      => 'mariadb',
        'port'      => 3306,
        'database'  => 'atd',
        'username'  => 'sail',
        'password'  => 'password',
    ],

    'autologin' => true,
];