<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/ProductController.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $controller = new ProductController($conn);
    $controller->delete($id);
} else {
    header("Location: ../../views/products/index.php");
    exit;
}
