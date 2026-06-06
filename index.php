<?php
// Autoloader dari composer untuk load Bramus Router
require_once __DIR__ . '/vendor/autoload.php';

// Inisialisasi Router
$router = new \Bramus\Router\Router();

// Route untuk Dashboard / Beranda
$router->get('/', function() {
    require_once __DIR__ . '/views/home.php';
});

// Route untuk halaman Katalog Produk
$router->get('/katalog-produk', function() {
    // Redirect atau memuat langsung file katalog,
    // tapi karena katalog produk ada di view/products/katalog-produk.php
    require_once __DIR__ . '/views/products/katalog-produk.php';
});

// Route untuk halaman Detail Produk (Dinamic ID)
$router->get('/view/product/katalog-produk/(\d+)', function($id) {
    // Simpan ID ke dalam variabel yang bisa diakses di file view
    $productId = $id;
    require_once __DIR__ . '/views/products/detail-produk.php';
});

// Jalankan Router
$router->run();
