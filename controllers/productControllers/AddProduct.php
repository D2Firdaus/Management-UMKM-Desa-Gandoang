<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/ProductController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductController($conn);
    $controller->store();
} else {
    header("Location: ../../views/products/index.php");
    exit;
}
