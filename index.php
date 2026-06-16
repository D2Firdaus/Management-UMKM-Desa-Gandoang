<?php
require_once __DIR__ . '/vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->get('/', function () {
    require_once __DIR__ . '/config/koneksi.php';
    require_once __DIR__ . '/controllers/HomeController.php';
    $controller = new HomeController($conn);
    $controller->index();
});

$router->get('/katalog-produk', function () {
    require_once __DIR__ . '/config/koneksi.php';
    require_once __DIR__ . '/controllers/productControllers/KatalogProdukController.php';
    $controller = new KatalogProdukController($conn);
    $controller->index();
});

$router->get('katalog-produk/([^/]+)', function ($id) {
    require_once __DIR__ . '/config/koneksi.php';
    require_once __DIR__ . '/controllers/productControllers/DetailProdukController.php';
    $controller = new DetailProdukController($conn);
    $controller->show($id);
});

$router->run();
