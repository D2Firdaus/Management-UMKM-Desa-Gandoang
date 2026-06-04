<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header("Location: index.php");
    exit;
}

$controller = new ProductController($conn);
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

                    <form action="<?= $product_controller_path ?>DeleteProduct.php?id=<?= $product['id_produk'] ?>" method="POST">

                        <div class="mb-4 p-3 bg-light rounded-3" style="font-size: 18px;">
                            <p><strong>Nama Produk :</strong> <?= htmlspecialchars($product['nama_produk']) ?></p>
                            <p class="mb-0"><strong>Deskripsi :</strong> <?= htmlspecialchars($product['deskripsi'] ?? '-') ?></p>
                        </div>

                        <div class="text-center mb-4">
                            <p class="fs-5 fw-bold text-danger">
                                Yakin ingin menghapus produk <em><?= htmlspecialchars($product['nama_produk']) ?></em>?
                            </p>
                            <p class="text-muted">Setelah dihapus maka akan hilang dari daftar produk.</p>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-batal fw-bold" style="width: 10rem;">Batal</a>
                            <button type="submit" class="btn btn-simpan fw-bold d-flex justify-content-center align-items-center gap-2" style="width: 10rem; background-color: #dc3545;">
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
    <?php ob_end_flush(); ?>
</body>

</html>
