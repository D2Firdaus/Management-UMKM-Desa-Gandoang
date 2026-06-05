<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$id = $_GET['id'];

if (!$id) {
    header("Location: index.php");
    exit;
}

$controller = new ProductController($conn);
$product = $controller->getProductById($id);
$product    = $controller->getProductById($id);

if (!$product) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk</title>

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= $asset_path ?>boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $asset_path ?>css/products/addProducts.css">
    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>

        <div class="main">
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content container-fluid">
                <div class="form-card shadow-sm">
                    <h2 class="text-center fw-bold mb-5" style="color: #65835e;">Form Hapus Produk</h2>
                    <form action="<?= $product_controller_path ?>DeleteProduct.php?id=<?= $product['id_produk'] ?>" method="POST" enctype="multipart/form-data">
                        <div style="margin-bottom: 90px; font-size: 20px;">
                            Nama Produk : <?= htmlspecialchars($product['nama_produk']); ?><br>
                            Deskripsi Produk : <?= htmlspecialchars($product['deskripsi']); ?>
                        </div>
                        <div style="text-align: center; font-size: 26px; margin-bottom: 120px;">
                            Hapus Produk <?= htmlspecialchars($product['nama_produk']) ?>?<br>
                            Setelah Dihapus Maka Akan Hilang Dari Daftar
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-batal fw-bold fs-4" style="width: 10rem;">Batal</a>
                            <button type="submit" class="justify-content-center btn btn-simpan fw-bold d-flex align-items-center gap-2" style="width: 10rem;">
                                <i class="bi bi-floppy"></i> Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
    <?php ob_end_flush(); ?>
</body>

</html>
