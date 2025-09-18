<?php

require __DIR__.'/../vendor/autoload.php';

try {
    $manager = new \Intervention\Image\ImageManager(
        new \Intervention\Image\Drivers\Gd\Driver()
    );
    echo 'GD sürücüsü başarıyla yüklendi.';
} catch (Exception $e) {
    echo 'GD sürücüsü hatası: ' . $e->getMessage();
}
