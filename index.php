<?php
// Autoloader dari composer untuk load Bramus Router
require_once __DIR__ . '/vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->get('/', function () {
    require_once __DIR__ . '/views/home.php';
});

$router->get('/katalog-produk', function () {

    require_once __DIR__ . '/views/products/katalog-produk.php';
});

$router->get('/view/product/katalog-produk/(\d+)', function ($id) {
    $productId = $id;
    require_once __DIR__ . '/views/products/detail-produk.php';
});

// Jalankan Router
$router->run();
