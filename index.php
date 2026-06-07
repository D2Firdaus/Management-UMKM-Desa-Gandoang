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

$router->get('/view/product/katalog-produk/([^/]+)', function ($id) {
    require_once __DIR__ . '/config/koneksi.php';
    require_once __DIR__ . '/controllers/productControllers/DetailProdukController.php';
    $controller = new DetailProdukController($conn);
    $controller->show($id);
});

// Jalankan Router
$router->run();
