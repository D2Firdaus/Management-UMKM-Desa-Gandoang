<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/ProductController.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $controller = new ProductController($conn);
    $controller->delete($id);
} else {
    header("Location: ../../views/products/index.php");
    exit;
}
