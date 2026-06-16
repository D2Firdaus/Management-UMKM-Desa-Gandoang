<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$controller = new ProductController($conn);
$product    = $controller->getProductById($id);
$product    = $controller->getProductById($id);

if (!$product) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk - UMKM Gandoang</title>

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= $asset_path ?>boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $asset_path ?>css/products/addProducts.css">
    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>

        <div class="main">
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">
                <div class="form-card shadow-sm">
                    <h2 class="text-center fw-bold mb-5" style="color: #65835e;">Hapus Produk</h2>

                    <form action="<?= $product_controller_path ?>DeleteProduct.php?id=<?= $product['id_produk'] ?>" method="POST">

                        <div class="hapus-info">
                            Nama Produk : <?= htmlspecialchars($product['nama_produk']) ?><br>
                            Deskripsi : <?= htmlspecialchars($product['deskripsi']) ?>
                        </div>

                        <div class="hapus-konfirmasi">
                            Hapus produk <strong><?= htmlspecialchars($product['nama_produk']) ?></strong>?<br>
                            Setelah dihapus, produk tidak akan muncul di daftar.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-batal fw-bold" style="width: 10rem;">Batal</a>
                            <button type="submit" class="btn btn-hapus fw-bold d-flex align-items-center justify-content-center gap-2" style="width: 10rem;">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
</body>

</html>
